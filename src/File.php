<?php

namespace SoundCloud;

/**
 * SoundCloud uploadable file representation class. Supports the CURLFile
 * class with graceful fallback.
 */
if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
    class File extends \CURLFile
    {
        public function __construct($path)
        {
            if (strpos($path, '@') === 0) {
                $path = substr($path, 1);
            }

            if (!file_exists($path)) {
                throw new FileDoesNotExistException();
            }

            if (!is_readable($path)) {
                throw new FileIsNotReadableException();
            }

            $info = pathinfo($path);
            $mimeType = File\Format::getMimeType(
                $info['extension']
            );
            $name = $info['basename'];

            parent::__construct($path, $mimeType, $name);
        }

        public function getPostField()
        {
            return $this;
        }
    }
} else {
    class File
    {
        private $path;

        public function __construct($path)
        {
            if (!file_exists($path)) {
                error_log($path);
                throw new FileDoesNotExistException();
            }

            if (!is_readable($path)) {
                throw new FileIsNotReadableException();
            }

            $this->path = $path;
        }

        public function getPostField()
        {
            return $this->path;
        }
    }
}
