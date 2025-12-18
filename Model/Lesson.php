<?php
class Lesson
{
    private ?int $id;
    private ?string $title;
    private ?string $content;
    private ?string $videoUrl;
    private ?int $duration;
    private ?int $module_id;

    public function __construct(
        ?int $id = null,
        ?string $title = null,
        ?string $content = null,
        ?string $videoUrl = null,
        ?int $duration = null,
        ?int $module_id = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->videoUrl = $videoUrl;
        $this->duration = $duration;
        $this->module_id = $module_id;
    }

    public function getId(): ?int { return $this->id; }
    public function getTitle(): ?string { return $this->title; }
    public function getContent(): ?string { return $this->content; }
    public function getVideoUrl(): ?string { return $this->videoUrl; }
    public function getDuration(): ?int { return $this->duration; }
    public function getModuleId(): ?int { return $this->module_id; }

    public function setId(?int $id) : void { $this->id = $id; }
    public function setTitle(?string $title) : void { $this->title = $title; }
    public function setContent(?string $content) : void { $this->content = $content; }
    public function setVideoUrl(?string $videoUrl) : void { $this->videoUrl = $videoUrl; }
    public function setDuration(?int $duration) : void { $this->duration = $duration; }
    public function setModuleId(?int $module_id) : void { $this->module_id = $module_id; }
}
?>
