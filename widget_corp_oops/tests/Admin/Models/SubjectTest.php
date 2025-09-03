<?php

namespace Widget_Corp_Oops_Tests\Admin\Models;

use Widget_Corp_Oops_Admin\Models\Subject;
use Widget_Corp_Oops_Tests\Database\DatabaseTestCase;
use Widget_Corp_Oops_Helper\DBConnection;

class SubjectTest extends DatabaseTestCase
{
    private function makeSubject(): Subject
    {
        $db = new DBConnection($this->conn);
        return new Subject($db);
    }

    public function testCreateNewSubject(): void
    {
        $subject = $this->makeSubject();

        $id = $subject->createNewSubject('Math', 1, true);

        $this->assertIsInt($id);
        $fetched = $subject->getSubjectById($id);
        $this->assertNotNull($fetched);
        $this->assertSame('Math', $fetched['menu_name']);
        $this->assertSame(1, (int) $fetched['position']);
        $this->assertSame(1, (int) $fetched['visible']);
    }

    public function testGetSubjects(): void
    {
        $subject = $this->makeSubject();

        $subject->createNewSubject('Science', 2, true);
        $subject->createNewSubject('History', 3, false);

        $all = $subject->getSubjects();

        $this->assertIsArray($all);
        $this->assertGreaterThanOrEqual(2, count($all));
        $this->assertArrayHasKey('menu_name', $all[0]);
        $this->assertArrayHasKey('position', $all[0]);
        $this->assertArrayHasKey('visible', $all[0]);
    }

    public function testUpdateSubject(): void
    {
        $subject = $this->makeSubject();

        $id = $subject->createNewSubject('Geography', 4, true);
        $rows = $subject->updateSubject($id, 'World Geography', 5, false);

        $this->assertSame(1, $rows);
        $updated = $subject->getSubjectById($id);
        $this->assertNotNull($updated);
        $this->assertSame('World Geography', $updated['menu_name']);
        $this->assertSame(5, (int) $updated['position']);
        $this->assertSame(0, (int) $updated['visible']);
    }

    public function testDeleteSubject(): void
    {
        $subject = $this->makeSubject();

        $id = $subject->createNewSubject('Art', 6, true);
        $deleted = $subject->deleteSubjectById($id);

        $this->assertSame(1, $deleted);
        $fetched = $subject->getSubjectById($id);
        $this->assertNull($fetched);
        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);
        $this->assertSame(0, $subject->deleteSubjectById($id)); // deleting again does nothing
    }

    public function testCreateDuplicateSubjectFails(): void
    {
        $this->expectException(\RuntimeException::class);

        $subject = $this->makeSubject();

        $subject->createNewSubject('Physics', 7, true);
        $subject->createNewSubject('Physics', 8, false); // duplicate
    }
}
