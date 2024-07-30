<?php
class Xlsxexport {
    private $handler;
    private $filename;
    
    public function __construct() {
        $this->handler = new XLSXWriter();
        $this->handler->setAuthor('');
    }
    
    public function deleteExportFile($file_name) {
        unlink(DIR_UPLOAD . $file_name);
    }
    
    public function readExportFile($file_name) {
        return file_get_contents(DIR_UPLOAD . $file_name);
    }
    
    public function sizeExportFile($file_name) {
        return filesize(DIR_UPLOAD . $file_name);
    }
    
    public function writeHeader($sheet_name, $data_headers) {
        $this->handler->writeSheetHeader($sheet_name, $data_headers);
    }
    
    public function writeBulkData($sheet_name, $data_rows) {
        $this->handler->writeSheet($data_rows, $sheet_name);
    }
    
    public function writeRowData($sheet_name, $data_row) {
        $this->handler->writeSheetRow($sheet_name, $data_row);
    }
    
    public function exportFile($file_name) {
        $this->handler->writeToFile(DIR_UPLOAD . $file_name);
    }
    
    public function exportTempFile() {
        $this->handler->setTempDir(DIR_TEMPORARY);
        $this->handler->writeToStdOut();
    }
}