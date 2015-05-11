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
            $this->path = $path;
        }

        public function getPostField()
        {
            return $this->path;
        }
    }
}
