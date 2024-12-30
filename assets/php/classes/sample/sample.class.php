<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");
/**
 *  Contoh penggunaan Class
 * 	Nama Class bisa berupa apa saja dan akan digunakan untuk URL/link
 *  
 *  Nama Class ini ketika pada URL/link akan dirubah menjadi huruf kecil
 *  karena file ini atau sample.class.php berada dalam folder sample, maka URL/LINK yang digunakan
 *  adalah https://domain.com/sample/sampel/ atau https://domain.com/sample/sampel
 */
class Sampel extends _Controller{
	
	function __construct(){
		/*
			HARUS ADA:
			parent::__construct() dibawah berguna untuk mengambil nama class yang dimasukan pada _Controller dan menjadikan sebagai variabel local pada class ini
		*/
		parent::__construct();
		/**
		 * 	variable yang tersedia
		 */
		 
		/**
		 * 	- VIEW
		 * 		$this->view->setDir('nama_folder');
		 * 		$this->view->show('nama_file');
		 * 		$this->view->pindah('nama_class/fungsi_class');
		 *      $this->view->template('nama_file');
		 *      $this->view->templateShow('nama_file');
		 * 	    $this->view->text('nama_file');
		 *      $this->view->nonSlashPindah('nama_class/fungsi');
		 */
		 
		/** 
		 * 	- DATABASE
		 * 		$this->database->mysqli;
		 * 		$this->database->pdo;
		 * 		$this->database->backup();
		 */
		 
		/**  
		 * 	- EXCEL
		 * 		$this->excelreader;
		 */
		 
		/** 
		 * 	- BARCODE
		 * 		$this->barcode->simpan('nama_output', 'isi_barcode');
		 * 		$this->barcode->blob('isi_barcode', 'ukuran_gambar', $showtext = true|false);
		 * 		$this->barcode->image('isi_barcode', 'ukuran_gambar', $showtext = true|false);
		 * 		$this->barcode->base64en('isi_barcode', 'ukuran_gambar', $showtext = true|false);
		 */
		 
		/** 
		 * 	- QRCODE
		 * 		$this->qrcode-> .... # sesuai dengan https://github.com/codeitnowin/barcode-generator
		 */
		 
		 /**
		 * 	- PDF
		 * 		$this->pdf-> .... # sesuai dengan FPDF dari Olivier PLATHEY
		 * */
	}

	/**
	 * method/fungsi pertama yang akan dijalankan ketika class ini digunakan
	 * sesuai dengan yang ada pada class _Autoloader
	 * 
	 *  
	 */
	public function index(){
		/**
		 *  jika folder awal bukan templates dan ingin menggunakan template header, sidebar, footer,
		 *  atur folder awal ke template setelah itu atur kembali ke halaman yang akan ditampilkan
		 *  
		 *  CATATAN :
		 *  jika ingin memasukan data pada halaman, gunakan array('halaman'=>$isi_data);
		 *  pada halaman, gunakan variable $data untuk mengambil data yang dimasukan
		 * 
		 */

		/**
		 * menggunakan fungsi loadheader untuk mengambil templates awal yang akan digunakan pada 
		 * halaman 
		 */
		$this->loadHeader();
		/**
		 * 
		 *  karena folder sebelumnya adalah folder templates dan kita ingin mengganti ke folder
		 *  yang akan dijadikan halaman tampilan / page, kita ganti folder templates dengan
		 * 	folder yang akan digunakan
		 * 
		 *  untuk mengganti folder yang akan digunakan, ganti folder dengan menggunakan
		 *  setDir('nama_folder');
		 * 
		 */
		$this->view->setDir('dashboard');

		/**
		 *  metode pertama untuk menampilkan halaman
		 * 
		 */
		$this->view->show('main');

		/**
		 *  metode kedua untuk menampilkan halaman
		 *  menggunakan array untuk memuat halaman dalam satu variable
		 *  parameter : view->show(array('nama_file_1'=>$isi_data_1, 'nama_file_2'=>$isi_data_2, ..));
		 * 
		 */

		 /**
		  *  hapus komentar pada blok ini dan metode pertama untuk menggunakan metode kedua
		  * 
		  * 
		  *  $this->view->show(array('header'=>null, 'sidebar'=>null));
		  * 
		  */

		/**
		 *  menggunakan fungsi loadFooter yang telah dibuat untuk menampilkan halaman footer
		 * 
		 */
		$this->loadFooter();
	}
    
    /**
     * fungsi untuk membaca file excel
     */
	public function cekexcel(){
		/**
		 * 	jika dalam class,
		 *  untuk mengambil file, folder dihitung mulai dari index.php
		 * 
		 */
		$file = 'assets/doc/example.xls';
		if(file_exists($file)){
			$excel = $this->excelreader;
			$excel->Excel_Reader();
			$excel->setOutputEncoding('CP1251');
			$excel->read($file);
			for ($i = 1; $i <= $excel->sheets[0]['numRows']; $i++) {
				for ($j = 1; $j <= $excel->sheets[0]['numCols']; $j++) {
					if(isset($excel->sheets[0]['cells'][$i][$j])){
						echo "\"".$excel->sheets[0]['cells'][$i][$j]."\",";
					}
					else{
						echo '"",';
					}
				}
				echo "<br>";
			}
		}
		else{
			echo 't';
		}
	}
    
    /**
     * fungsi untuk menggunakan template
     */
    public function templates(){
        
        /**
         * fungsi untuk mengambil file yang terdapat dalam folder page/templates yang akan digunakan
         * parameter yang digunakan adalah nama dari file template.
         * file yang berada pada folder page/templates adalah template.view.php
         */
        $this->view->template('template');
        
        /**
         * mengatur folder dari content yang akan ditampilkan dan berada dalam folder page
         */
        $this->view->setDir('dashboard');
        
        /**
         * menampilkan template dan content
         * untuk mengirim data kedalam content, gunakan array asosiatif 
         * contoh :
         * 
         * $data = array('isi data');
         * $this->view->templateShow(['content'=>$data]);
         * 
         * atau 
         * 
         * $data = array('isi data');
         * $tampikan = array('content'=>$data);
         * $this->view->templateShow($tampilkan);
         */
        $this->view->templateShow('content');
        
    }

    /**
     * fungsi untuk menggunakan barcode
     */
	public function cekbarcode(){
		/**
		 *  fungsi yang ada pada barcode:
		 * 
		 *  - $this->barcode->base64en($text, $size="20", $showtext = true);
		 * 		- output: string base64_encode dari file barcode
		 * 
		 * 	- $this->barcode->image($text, $size="20", $showtext = true);
		 * 		- output: gambar barcode
		 * 		- syarat: $this->loadHeader() dan $this->loadFooter()
		 * 		  		  harus dihapus ketika menggunakan fungsi $this->barcode->image
		 * 
		 *  - $this->barcode->blob($text, $size="20", $showtext = true);
		 * 		- output: string blob dari file barcode
		 * 
		 *  - $this->barcode->simpan($nama, $text, $size="20", $showtext = true);
		 * 		- output: folder tempat menyimpan file barcode beserta nama file
		 * 		- folder tetap: assets/img/
		 * 		- catatan: 
		 * 				  - jika nama file sama, maka isi dari file barcode akan diubah
		 * 				  - jika ingin menyimpan dalam folder baru, buat dahulu folder menggunakan mkdir()
		 * 				  - jika membuat folder baru, variable $nama diisi dengan 
		 * 					$nama_folder.DIRECTORY_SEPARATOR.$nama_file
		 * 
		 */
		$simpan = $this->barcode->image('itext');
	}


	/**
	 *  method/fungsi untuk membuat QrCode
	 *  menggunakan class dari Akhtar Khan
	 *  https://github.com/codeitnowin/barcode-generator
	 */
	public function testqr(){
		$qr = $this->qrcode
		->setText('initext')
        ->setSize(300)
        ->setPadding(10)
        ->setErrorCorrection('high')
        ->setForeGroundColor(array('r'=>0, 'g'=>0, 'b'=>0, 'a'=>0))
        ->setBackGroundColor(array('r'=>255, 'g'=>255, 'b'=>255, 'a'=>0))
        ->setLabel('Laba-Laba')
        ->setLabelFontSize(15)
        ->setImageType('png')
        ->save('initestingqr.png');

        // dengan icon
        // $logo = realpath(__DIR__.'/../../../img/logo.png');
        // $qr = $this->qrcode
		// ->setText('initext')
        // ->setSize(300)
        // ->setPadding(10)
        // ->setErrorCorrection('high')
        // ->setForeGroundColor(array('r'=>0, 'g'=>0, 'b'=>0, 'a'=>0))
        // ->setBackGroundColor(array('r'=>255, 'g'=>255, 'b'=>255, 'a'=>0))
        // ->setLabel('Laba-Laba')
        // ->setLabelFontSize(15)
        // ->setIcon($logo)
        // ->setImageType('png')
        // ->save('initestingqr.png');
	}

    /**
     * fungsi untuk membuat pdf
     * menggunakan class dari Oliver PLATHEY (fpdf)
     */
	public function testpdf(){
		/**
		 *  menggunakan fungsi dari fpdf dari Olivier PLATHEY
		 * 
		 */
		$pdf = $this->pdf;
		$pdf->setMargins(20, 0);
		$pdf->AddPage();
		$pdf->setFont('Arial', 'B', 8.7);
		$pdf->Rect(5, 5, 85.6, 53.98);
		$pdf->Image('assets/img/itext.png', 5, 5.2, 85.4, 53.4);
		$pdf->Ln(12.4);
    	$pdf->Cell(23, 0, 'ini text', 0, 2, 'L');
    	$pdf->SetWidths(array('50'));
    	$pdf->Row(array('text dalam cell, ini sambungan text, text selanjutnya'), 3.5);
    	$pdf->Output('i', 'nama_file_ini.pdf', true);
	}

	/**
	 *  BACKUP DATABASE
	 *  fungsi untuk membuat backup database
	 * 
	 */

	public function backup(){
		/**
		 *  Backup database akan dimasukan pada folder database
		 *  folder database berada pada 1 level dengan folder assets
		 *  file backup database berupa tabel dalam database
		 * 
		 */
		$this->database->backup();
		// echo 'backup berhasil dibuat';
	}

	/**
	 *  DATABASE LEBIH DARI 1
	 *  
	 */

	public function manydb(){
		// koneksi database ke 1
		// karena menggunakan array untuk menyimpan parameter dalam $_PENGATURAN['DB']
		// maka, koneksi ke 1 atau pertama, ditulis sebagai 0
		$koneksi_ke = 0;
		$con = $this->database->getMysqli($koneksi_ke);
		var_dump($con);
	}

	/**
	 *  fungsi untuk menyimpan data ke database
	 *  nama dari fungsi ini, akan digunakan sebagai URL/LINK untuk action pada form
	 * 
	 */
	public function simpan(){
		/**
		 *  pada class database, bisa menggunakan PDO atau Mysqli
		 * 
		 *  proses simpan sederhana menggunakan mysqli
		 * 
		 * 
		 * 
		 * 	PENTING !
		 *  untuk keamanan data, bisa menggunakan fungsi/function dari PHP
		 *  contoh: hash, hash_hmac, base64_encode, dll..
		 * 
		 * 	
		 * 
		 * 
		 *  menginisalkan variable $this->database->mysqli menjadi $mysqli
		 * 
		 * 
		 */
		$mysqli = $this->database->mysqli;

		/**
		 *  $id
		 *  bisa menggunakan $_POST['id'] atau yang lain yang ingin digunakan
		 * 
		 */
		$id = hash('md5', microtime(true)); 

		/**
		 *  $nama
		 *  bisa menggunakan $_POST['nama'] atau yang lain yang ingin digunakan
		 * 
		 */
		$nama = hash('md5', microtime(true));

		/**
		 *  $akun
		 *  bisa menggunakan $_POST['akun'] atau yang lain yang ingin digunakan
		 * 
		 */
		$akun = hash('md5', microtime(true));

		/**
		 *  $password
		 *  bisa menggunakan $_POST['password'] atau yang lain yang ingin digunakan
		 * 
		 */
		$password = hash('md5', microtime(true));

		/**
		 *  $simpan
		 *  menginisialkan $mysqli->query() menjadi variable $simpan
		 * 
		 */		
		$simpan = $mysqli->query("INSERT INTO user(id, nama, akun, password) VALUES('$id', '$nama', '$akun', '$password')");

		/**
		 *  mengecek jika berhasil tersimpan
		 * 
		 */
		if($simpan){
			/**
			 *  mengalihkan halaman ke halaman tujuan menggunakan 
			 *  fungsi $this->view->pindah(nama folder / halaman tujuan)
			 *  contoh: sample/sampel/
			 */
			$this->view->pindah('sampel');
		}
		else{
			// tampilkan error
			echo $mysqli->error;
		}
	}
    
    /**
     * fungsi untuk menampilkan halaman detail
     * $id = string dari id data
     */
	public function detail($id = ''){
		/**
		 *  jika folder awal bukan pada halaman yang dituju, arahkan
		 *  ke folder yang diinginkan menggunakan fungsi setDir($nama_folder);
		 * 
		 */
		$this->view->setDir('dashboard');
		/**
		 *  gunakan array('halaman'=>$isidata);
		 *  gunakan variable $data ketika pada halaman
		 *  
		 * 
		 *  menginisalkan $this->database->mysqli menjadi $mysqli
		 * 
		 */
		$mysqli = $this->database->mysqli;


		/**
		 *  mengambil data pada database dan menyimpannya dalam variable $ambil
		 * 
		 */
		$ambil  = $mysqli->query("SELECT * FROM user WHERE id='$id'");

		/**
		 *  memasukan data yang telah diambil kedalam halaman yang akan ditampilkan
		 * 
		 */
		$isi = array('sampel'=>$ambil);

		/**
		 *  menampilkan templates yang telah dimuat dalam fungsi loadHeader
		 * 
		 */
		$this->loadHeader();
		
		/**
		 *  menampilkan halaman beserta data
		 * 
		 *  gunakan variable $data pada halaman yang akan ditampilkan untuk mengambil isi data
		 *  yang telah dimasukan
		 * 
		 */

		$this->view->show($isi);

		/**
		 *  menampilkan templates footer yang telah dimuat dalam fungsi loadFooter
		 * 
		 */
		$this->loadFooter();
	}
    
    /**
     * fungsi untuk menghapus data pada database
     */
	public function hapus($id = ''){
		/**
		 *  bisa menggunakan method mysqli atau PDO
		 *  
		 * 
		 *  untuk pindah/redirect halaman setelah menghapus
		 *  gunakan $this->view->pindah('url')
		 * 	atau gunakan $this->view->pindah('nama_class/fungsi_class')
		 */
		$this->view->pindah('sampel');
	}

	/**
	 *  fungsi yang digunakan untuk mengambil templates header 
	 * 
	 */
	private function loadHeader(){
		/**
		 *  menentukan folder templates yang akan digunakan
		 *  folder templates harus berada di dalam folder page
		 * 
		 */
		$this->view->setDir('templates');

		/**
		 *  menampilkan file header pada folder templates
		 *  CATATAN :
		 *  jika ingin memasukan data pada halaman, gunakan array('file_halaman'=>$isi_data);
		 *  pada halaman, gunakan variable $data untuk mengambil data yang dimasukan
		 *
		 */
		$this->view->show('header');

		/**
		 *  menampilkan file sidebar pada folder templates
		 *
		 */
		$this->view->show('sidebar');
	}

	/**
	 *  fungsi yang digunakan untuk menampilkan templates footer
	 * 
	 */
	private function loadFooter(){
		/**
		 *  merubah folder yang akan digunakan
		 * 
		 */
		$this->view->setDir('templates');

		/**
		 *  mengambil file yang akan ditampilkan
		 * 
		 */
		$this->view->show('footer');
	}


}