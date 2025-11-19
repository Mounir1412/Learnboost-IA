<?php

if (!class_exists('Quiz')) {

    class Quiz {

        private ?int $id;
        private string $title;
        private int $time_in_minutes;
        private string $description;
       

        public function __construct(
            string $title,
            int $time_in_minutes,
            string $description,
        ) {
            $this->id = null;
            $this->title = $title;
            $this->time_in_minutes = $time_in_minutes;
            $this->description = $description;
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getTitle(): string
        {
            return $this->title;
        }

        public function getTimeInMinutes(): int
        {
            return $this->time_in_minutes;
        }

        public function getDescription(): string
        {
            return $this->description;
        }

        
        public function setTitle(string $title): void
        {
            $this->title = $title;
        }

        public function setTimeInMinutes(int $time_in_minutes): void
        {
            $this->time_in_minutes = $time_in_minutes;
        }

        public function setDescription(string $description): void
        {
            $this->description = $description;
        }
        public function setId(int $id): void
        {
            $this->id = $id;
        }
    }
}
?>