<?php
/**
 * SoundCloud uploadable file representation class. Supports the CURLFile
 * class with graceful fallback.
 */
if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
    class Services_Soundcloud_File extends CURLFile
    {
        function __construct($path)
        {
            if (strpos($path, '@') === 0) {
                $path = substr($path, 1);
            }

            $info = pathinfo($path);
            $mimeType = Services_Soundcloud_File_Format::getMimeType(
                $info['extension']
            );
            $name = $info['basename'];

            parent::__construct($path, $mimeType, $name);
        }

        function getPostField()
        {
            return $this;
        }
    }
} else {
    class Services_Soundcloud_File
    {
        private $path;

        function __construct($path)
        {
            $this->path = $path;
        }

        function getPostField()
        {
            return $this->path;
        }
    }
}
?>
