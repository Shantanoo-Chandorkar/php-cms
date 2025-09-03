<?php

namespace Widget_Corp_Oops_Tests\Admin\Models;

use Widget_Corp_Oops_Admin\Models\Page;
use Widget_Corp_Oops_Tests\Database\DatabaseTestCase;
use Widget_Corp_Oops_Helper\DBConnection;

class PageTest extends DatabaseTestCase
{
    private function makePage(): Page
    {
        $db = new DBConnection($this->conn);
        return new Page($db);
    }

    public function testCreateNewPage(): void
    {
        $page = $this->makePage();

        $id = $page->createNewPage(1, 'About Us', 1, true, 'Welcome to our site!');

        $this->assertIsInt($id);
        $fetched = $page->getPageById($id);
        $this->assertNotNull($fetched);
        $this->assertSame('About Us', $fetched['menu_name']);
        $this->assertSame(1, (int) $fetched['position']);
        $this->assertSame('Welcome to our site!', $fetched['content']);
    }

    public function testGetPagesBySubjectId(): void
    {
        $page = $this->makePage();

        $page->createNewPage(2, 'Products', 1, true, 'Product listing');
        $page->createNewPage(2, 'Services', 2, false, 'Our services');

        $all = $page->getPagesBySubjectId(2);

        $this->assertIsArray($all);
        $this->assertGreaterThanOrEqual(2, count($all));
        $this->assertSame(2, (int) $all[0]['subject_id']);
        $this->assertArrayHasKey('menu_name', $all[0]);
        $this->assertArrayHasKey('content', $all[0]);
    }

    public function testUpdatePageById(): void
    {
        $page = $this->makePage();

        $id = $page->createNewPage(3, 'Old Page', 1, true, 'Old content');
        $success = $page->updatePageById($id, 'New Page', 2, false, 'Updated content');

        $this->assertTrue($success);
        $updated = $page->getPageById($id);
        $this->assertNotNull($updated);
        $this->assertSame('New Page', $updated['menu_name']);
        $this->assertSame(2, (int) $updated['position']);
        $this->assertSame('Updated content', $updated['content']);
    }

    public function testDeletePageById(): void
    {
        $page = $this->makePage();

        $id = $page->createNewPage(4, 'Temp Page', 1, true, 'Delete me');
        $deleted = $page->deletePageById($id);

        $this->assertTrue($deleted);
        $fetched = $page->getPageById($id);
        $this->assertNull($fetched);
        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);
        $this->assertFalse($page->deletePageById($id)); // deleting again does nothing
    }

    public function testCountPagesForSubject(): void
    {
        $page = $this->makePage();

        $page->createNewPage(10, 'Page One', 1, true, 'First');
        $page->createNewPage(10, 'Page Two', 2, true, 'Second');

        $count = $page->countPagesForSubject(10);

        $this->assertIsInt($count);
        $this->assertSame(2, $count);
        $this->assertGreaterThanOrEqual(2, $count);
        $this->assertEquals(0, $page->countPagesForSubject(9999));
        $this->assertNotEmpty($count);
    }

    public function testGetFirstPositionPageBySubjectId(): void
    {
        $page = $this->makePage();

        $page->createNewPage(10, 'First Page', 1, true, 'Content one');
        $page->createNewPage(10, 'Second Page', 2, true, 'Content two');

        $first = $page->getFirstPositionPageBySubjectId(10);

        $this->assertNotNull($first);
        $this->assertIsArray($first);
        $this->assertSame('First Page', $first['menu_name']);
        $this->assertSame(1, (int) $first['position']);
        $this->assertSame('Content one', $first['content']);
    }
}
