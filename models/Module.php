<?php
class Module {
    private ?int $id_module = null;
    private ?int $course_id = null;
    private ?string $title = null;
    private ?string $description = null;

    /**
     * Constructor compatible with two call styles:
     *  - new Module($course_id, $title, $description)
     *  - new Module($id, $course_id, $title, $description)
     */
    public function __construct(...$args) {
        if (count($args) === 3) {
            [$course_id, $title, $description] = $args;
            $this->course_id = $course_id;
            $this->title = $title;
            $this->description = $description;
        } elseif (count($args) === 4) {
            [$id, $course_id, $title, $description] = $args;
            $this->id_module = $id;
            $this->course_id = $course_id;
            $this->title = $title;
            $this->description = $description;
        }
    }

    public function getIdModule() {
        return $this->id_module;
    }

    public function getCourseId() {
        return $this->course_id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setCourseId($course_id) {
        $this->course_id = $course_id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
}