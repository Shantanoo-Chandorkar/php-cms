<?php

namespace Widget_Corp_Oops_Tests\Frontend\Controllers;

use PHPUnit\Framework\TestCase;
use Widget_Corp_Oops_Frontend\Controllers\HomeController;
use Widget_Corp_Oops_Frontend\Services\NavigationService;
use Widget_Corp_Oops_Admin\Models\Subject;
use Widget_Corp_Oops_Admin\Models\Page;

class HomeControllerTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject&NavigationService */
    private $navServiceMock;

    /** @var \PHPUnit\Framework\MockObject\MockObject&Subject */
    private $subjectMock;

    /** @var \PHPUnit\Framework\MockObject\MockObject&Page */
    private $pageMock;

    private $controller;

    protected function setUp(): void
    {
        $this->subjectMock = $this->createMock(Subject::class);
        $this->pageMock = $this->createMock(Page::class);
        $this->navServiceMock = $this->createMock(NavigationService::class);

        $this->controller = new HomeController($this->navServiceMock, $this->subjectMock, $this->pageMock);
    }

    public function testResolveSelectionReturnsPageAndSubjectWhenPageIdGiven()
    {
        $pageData = ['id' => 1, 'subject_id' => 2];
        $subjectData = ['id' => 2, 'name' => 'Math'];

        $this->pageMock->method('getPageById')->with(1)->willReturn($pageData);
        $this->subjectMock->method('getSubjectById')->with(2)->willReturn($subjectData);

        $result = $this->callResolveSelection($this->controller, 999, 1);

        $this->assertEquals($pageData, $result['page']);
        $this->assertEquals($subjectData, $result['subject']);
    }

    public function testResolveSelectionReturnsFirstPageWhenSubjectIdGiven()
    {
        $subjectData = ['id' => 5, 'name' => 'Science'];
        $pageData = ['id' => 10, 'subject_id' => 5];

        $this->subjectMock->method('getSubjectById')->with(5)->willReturn($subjectData);
        $this->pageMock->method('getFirstPositionPageBySubjectId')->with(5)->willReturn($pageData);

        $result = $this->callResolveSelection($this->controller, 5, null);

        $this->assertEquals($subjectData, $result['subject']);
        $this->assertEquals($pageData, $result['page']);
    }

    public function testResolveSelectionReturnsNullsWhenNothingFound()
    {
        $this->pageMock->method('getPageById')->willReturn(null);
        $this->subjectMock->method('getSubjectById')->willReturn(null);

        $result = $this->callResolveSelection($this->controller, null, null);

        $this->assertNull($result['subject']);
        $this->assertNull($result['page']);
    }

    private function callResolveSelection(HomeController $controller, ?int $subjId, ?int $pageId): array
    {
        $refMethod = new \ReflectionMethod(HomeController::class, 'resolveSelection');
        $refMethod->setAccessible(true);
        return $refMethod->invoke($controller, $subjId, $pageId);
    }
}
