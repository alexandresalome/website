<?php

namespace Alom\WebsiteBundle\Upload;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Storage
{
    protected $folder;
    protected $debug;

    public function __construct($folder, $debug = false)
    {
        $this->debug = $debug;

        if (!is_dir($folder) || !is_writable($folder)) {
            throw new \Exception(sprintf('The folder "%s" is not writable'));
        }

        $this->folder = $folder;
    }

    /**
     * Adds a file and return filename
     */
    public function addUpload(UploadedFile $upload, $subFolder = '')
    {
        // Prepare folder
        $folder = $this->folder . '/' . $subFolder;
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        // Find the filename
        do {
            $filename = md5(uniqid().microtime()).'.'.$upload->guessExtension();
        } while (file_exists($folder . '/' . $filename));

        // Store it
        $upload->move($folder, $filename);

        return $filename;
    }

    public function remove($file, $subFolder = '')
    {
        $file = $this->folder.'/'.$subFolder.'/'.$file;
        if (!file_exists($file) && true === $this->debug) {
            throw new \Exception(sprintf('Cannot delete file "%s" : does not exists', $file));
        }

        unlink($file);
    }
}
