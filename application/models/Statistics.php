<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics extends CI_model {

	/**
	 * Write statistics to Excel table
	 *
	 * @param  string $file File name
	 * @return void
	 */
	public function Write($file) {

		if (file_exists($file) &&
			NULL !== $this->session->userdata('Write_statistics') &&
			$this->session->userdata('Write_statistics')) {

			$this->session->unset_userdata('Write_statistics');

			$this->load->library('Excel');

			// Open file
			$fileType = 'Excel2007';
			$objReader = PHPExcel_IOFactory::createReader($fileType);
			$objPHPExcel = $objReader->load($file);

			$objPHPExcel->setActiveSheetIndex(0);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 

			$dateFound = FALSE;
			$sum = 0;
			$currentDate = date('Y.m.d');
			for ($row = 1; $row <= $highestRow; ++$row) {
				$value = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
				$sum = max($sum, $objWorksheet->getCellByColumnAndRow(1, $row)->getValue());
				if ($value == $currentDate) {
					$dateFound = TRUE;
					$objPHPExcel = $this->Update($objPHPExcel, $row, $sum, $currentDate);
				}
			}

			if (!$dateFound) {
				$objPHPExcel = $this->Update($objPHPExcel, $highestRow+1, $sum, $currentDate);
			}

			// Write the file
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
			$objWriter->save($file);
		}
	}

	/**
	 * Update data in Excel table
	 *
	 * @param obj    $objPHPExcel PHP Excel object
	 * @param int    $row         Row id
	 * @param int    $sum_old     Sum of items
	 * @param string $currentDate Current date
	 *
	 * @return obj $objPHPExcel PHP Excel object.
	 */
	public function Update($objPHPExcel, $row, $sum_old, $currentDate) {

		$this->load->model('Database');
		
		$sum_new = $this->db->count_all_results('exercises');

		if ($sum_new > $sum_old) {

			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$row, $currentDate)
						->setCellValue('B'.$row, $sum_new);
		}

		return $objPHPExcel;
	}
}

?>