<?php

/**
 * ODT Template Processing and PDF Generation Class
 * 
 * This class provides functionality to process OpenDocument Text (ODT) templates
 * with placeholder substitution and automatic PDF conversion using LibreOffice.
 * 
 * REQUIREMENTS:
 * - PHP with shell_exec() enabled
 * - unzip command available in system PATH
 * - LibreOffice (soffice) installed for PDF conversion
 * 
 * BASIC USAGE:
 * 
 * 1. Create an ODT template file with placeholders
 * 2. Initialize the ODT class with template path
 * 3. Prepare the template (extracts and loads content)
 * 4. Replace placeholders with your data
 * 5. Generate output ODT and PDF files
 * 
 * EXAMPLE:
 * 
 *   $odt = new ODT('/path/to/template.odt');
 *   
 *   if ($odt->prepare()) {
 *       $data = [
 *           'customer_name' => 'John Doe',
 *           'invoice_date' => time(),
 *           'total_amount' => 1250.50,
 *           'items' => [
 *               ['name' => 'Product A', 'price' => 500.00, 'qty' => 1],
 *               ['name' => 'Product B', 'price' => 750.50, 'qty' => 1]
 *           ]
 *       ];
 *       
 *       $odt->replace_placeholders($data);
 *       $pdf_path = $odt->create_output('/path/to/output.odt');
 *       
 *       if ($pdf_path) {
 *           echo "PDF created: " . $pdf_path;
 *       } else {
 *           echo "Error: " . $odt->error_msg;
 *       }
 *   } else {
 *       echo "Error: " . $odt->error_msg;
 *   }
 * 
 * PLACEHOLDER SYNTAX:
 * 
 * In your ODT template, use LibreOffice placeholders (Insert > Field > Other > Variables > User Field)
 * or direct placeholder markup:
 * 
 * Simple placeholders:
 *   <text:placeholder>customer_name</text:placeholder>
 * 
 * Formatted placeholders with JSON properties in description:
 *   <text:placeholder text:description='{"format":"date"}'>invoice_date</text:placeholder>
 *   <text:placeholder text:description='{"format":"currency","decimals":2}'>total_amount</text:placeholder>
 * 
 * SUPPORTED FORMATS:
 * 
 * - "date" - Formats timestamp as date (uses cfg('date_format') or custom template)
 * - "time" - Formats timestamp as time (uses cfg('time_format') or custom template)
 * - "datetime" - Formats timestamp as datetime (uses cfg('datetime_format') or custom template)
 * - "currency" - Formats number as currency with € symbol
 * - "number" - Formats number with specified decimal places
 * - "duration" - Converts seconds to "Xh Ymin" format
 * 
 * FORMAT OPTIONS:
 * 
 * - "template": Custom date/time format string (e.g., "Y-m-d H:i:s")
 * - "decimals": Number of decimal places for currency/number formatting
 * - "default": Default value if placeholder data is empty
 * - "emptyifzero": Show empty string if numeric value is zero
 * - "delsegmentifzero": Delete entire segment if numeric value is zero
 * - "newline": Add newlines "before", "after", or "before,after" the value
 * 
 * LISTS AND TABLES:
 * 
 * For repeating table rows, use a special placeholder in a table row:
 *   <text:placeholder text:placeholder-type="table">items</text:placeholder>
 * 
 * This will repeat the table row for each item in the 'items' array.
 * Within the repeated row, access item properties with 'item.' prefix:
 *   <text:placeholder>item.name</text:placeholder>
 *   <text:placeholder>item.price</text:placeholder>
 * 
 * The class automatically calculates sums for numeric fields:
 *   <text:placeholder>sums.items.price</text:placeholder>
 * 
 * ERROR HANDLING:
 * 
 * Always check the return values and $odt->error_msg for error details:
 * - prepare() returns false on failure
 * - create_output() returns false on failure, PDF path on success
 * - Check $odt->error_msg for specific error messages
 * 
 * PROPERTIES:
 * 
 * - $template_name: Path to the ODT template file
 * - $content_xml: Loaded and processed content.xml from ODT
 * - $temp_dir: Temporary directory for ODT extraction
 * - $error_msg: Last error message
 * - $odt_filename: Path to generated ODT file
 * - $pdf_filename: Path to generated PDF file
 * - $debug_output: Debug output from shell commands
 * 
 * NOTES:
 * 
 * - Temporary files are automatically cleaned up in destructor
 * - PDF conversion requires LibreOffice to be installed and accessible
 * - The class assumes UTF-8 encoding for all text content
 * - Newlines in data are converted to ODT line breaks
 * - All string values are properly escaped for XML
 */

class ODT
{

	public $template_name;
	public $content_xml;
	public $temp_dir;
	public $error_msg;
	public $odt_filename;
	public $pdf_filename;
	public $debug_output;

	function __construct($template_name)
	{
		$this->template_name = $template_name;
		if(!file_exists($this->template_name)) {
			$this->error_msg = 'Template not found: '.$template_name;
		}
		$this->temp_dir = '/tmp/'.uniqid();
	}

	/**
	 * Check if all required command line tools are available and working
	 * @return array Array with 'status' (bool) and 'messages' (array of status messages)
	 */
	static function check_requirements()
	{
		$results = [
			'status' => true,
			'messages' => []
		];

		$unzip_check = shell_exec('which unzip 2>/dev/null');
		if (empty(trim($unzip_check))) {
			$results['status'] = false;
			$results['messages'][] = 'ERROR: unzip command not found in PATH';
		} else {
			$results['messages'][] = 'OK: unzip found at ' . trim($unzip_check);
			$unzip_version = shell_exec('unzip -v 2>&1 | head -1');
			if ($unzip_version) {
				$results['messages'][] = 'INFO: ' . trim($unzip_version);
			}
		}

		$zip_check = shell_exec('which zip 2>/dev/null');
		if (empty(trim($zip_check))) {
			$results['status'] = false;
			$results['messages'][] = 'ERROR: zip command not found in PATH';
		} else {
			$results['messages'][] = 'OK: zip found at ' . trim($zip_check);
		}

		$soffice_check = shell_exec('which soffice 2>/dev/null') || '';
		if (empty(trim($soffice_check))) {
			$results['status'] = false;
			$results['messages'][] = 'ERROR: LibreOffice (soffice) not found in PATH';
		} else {
			$results['messages'][] = 'OK: LibreOffice found at ' . trim($soffice_check);
			
			$soffice_version = shell_exec('soffice --version 2>&1');
			if ($soffice_version) {
				$results['messages'][] = 'INFO: ' . trim($soffice_version);
			}

			$headless_test = shell_exec('timeout 10 soffice --headless --help 2>&1') || '';
			if (strpos($headless_test, 'headless') !== false || strpos($headless_test, 'convert-to') !== false) {
				$results['messages'][] = 'OK: LibreOffice headless mode is working';
			} else {
				$results['status'] = false;
				$results['messages'][] = 'ERROR: LibreOffice headless mode test failed';
				$results['messages'][] = 'DEBUG: ' . trim($headless_test);
			}
		}

		if (!is_dir('/tmp')) {
			$results['status'] = false;
			$results['messages'][] = 'ERROR: /tmp directory does not exist';
		} elseif (!is_writable('/tmp')) {
			$results['status'] = false;
			$results['messages'][] = 'ERROR: /tmp directory is not writable';
		} else {
			$results['messages'][] = 'OK: /tmp directory exists and is writable';
		}

		$test_temp_dir = '/tmp/odt_test_' . uniqid();
		if (!mkdir($test_temp_dir, 0777, true)) {
			$results['status'] = false;
			$results['messages'][] = 'ERROR: Cannot create temporary directories in /tmp';
		} else {
			$results['messages'][] = 'OK: Can create temporary directories';
			rmdir($test_temp_dir);
		}

		return $results;
	}

    static function parse_attributes($tag) {
        $attributes = [];
        while($tag != '')
        {
            $a_name = nibble('="', $tag);
            $a_value = nibble('"', $tag);
            $attributes[trim($a_name)] = trim($a_value);
        }
        return $attributes;
    }

	function prepare()
	{
		// Create the temporary directory
		if (!mkdir($this->temp_dir, 0777, true)) {
			$this->error_msg = 'Failed to create temp directory';
			return false; 
		}

		// Unzip the template into the temp directory using shell_exec()
		$command = 'unzip ' . escapeshellarg($this->template_name) . ' -d ' . escapeshellarg($this->temp_dir) . ' 2>&1';
		$output = shell_exec($command);
		if ($output === null) {
			$this->error_msg = 'Failed to unzip the template';
			return false;
		}

		// Load content.xml into memory
		$content_file = $this->temp_dir . '/content.xml';
		if (!file_exists($content_file)) {
			$this->error_msg = 'content.xml not found';
			return false;
		}

		$this->content_xml = file_get_contents($content_file);
		if ($this->content_xml === false) {
			$this->error_msg = 'Failed to read content.xml';
			return false;
		}

		return true;
	}

	function odt_entities($raw)
	{
		return str_replace(chr(10), '<text:line-break/>', htmlspecialchars($raw, ENT_XML1 | ENT_QUOTES, 'UTF-8'));
	}

	function break_into_segments($s)
	{
		$segment_handlers = [
			'<table:table-row ' => function(&$cake, &$seg) {
				$rest_of_row = nibble('</table:table-row>', $cake);
				# check whether this segment contains an items marker indicating we should
				# iterate this segment over a list in the data
				$items_marker = '<text:placeholder text:placeholder-type="table">';
				if(strpos($rest_of_row, $items_marker) !== false)
				{
					$r0 = nibble($items_marker, $rest_of_row);
					$items_param = trim(str_replace(['&lt;', '&gt;'], '',
						nibble('</text:placeholder>', $rest_of_row)));
					$seg[] = [
						'type' => 'list',
						'content' => '<table:table-row '.$r0.$rest_of_row.'</table:table-row>',
						'fields' => $items_param];
				}
				else $seg[] = [
					'type' => 'flat',
					'content' => '<table:table-row '.$rest_of_row.'</table:table-row>'];
			},
		];
		$seg = [];
		while($s != '')
		{
			$seg_start_found = false;
			$sc = nibble(array_keys($segment_handlers), $s, $seg_start_found);
			$seg[] = ['type' => 'flat', 'content' => $sc];
			if($seg_start_found !== false)
			{
				$segment_handlers[$seg_start_found]($s, $seg);
			}
		}
		return($seg);
	}

	function run_segment($seg, $params)
	{
		$result = '';
		$sc = $seg['content'];
		while($sc != '')
		{
			$placeholder_found = false;
			$result .= nibble('<text:placeholder', $sc, $placeholder_found);
			if($placeholder_found)
			{
				$p_attributes = ODT::parse_attributes(nibble('>', $sc));
				$p_prop = [];
				if(isset($p_attributes['text:description']) && $p_attributes['text:description'])
				{
					if(substr($p_attributes['text:description'], 0, 1) == '{')
					{
						$decoded = json_decode(
							str_replace('&quot;', '"', $p_attributes['text:description']), true);
						if($decoded !== null) {
							$p_prop = $decoded;
						}
					}
					else
					{
						$p_prop['format'] = $p_attributes['text:description'];
					}
				}
				$p_content = str_replace(array('&gt;', '&lt;'), '',
					nibble('</text:placeholder>', $sc));
				$placeholder_key = $p_content;
				$value = first($params[$placeholder_key] ?? null, $p_prop['default'] ?? null, '');
				switch($p_prop['format'] ?? '')
				{
					case('date'):
					{
						if($value == 0) $value = '-'; else
						$value = date(first($p_prop['template'], cfg('date_format')), intval($value));
					} break;
					case('time'):
					{
						if($value == 0) $value = '-'; else
						$value = date(first($p_prop['template'], cfg('time_format')), intval($value));
					} break;
					case('datetime'):
					{
						if($value == 0) $value = '-'; else
						$value = date(first($p_prop['template'], cfg('datetime_format')), intval($value));
					} break;
					case('currency'):
					{
						$value = number_format(floatval($value), first($p_prop['decimals'] ?? null, 2), ',', '').' €';
					} break;
					case('number'):
					{
						$value = number_format(floatval($value), first($p_prop['decimals'] ?? null, 2), ',', '');
					} break;
					case('duration'):
					{
						$value = intval($value);
						$h = floor($value/3600); $value -= $h*3600;
						$m = floor($value/60); $value -= $m*60;
						$s = $value;
						$value = '';
						if($h > 0) $value .= $h.'h ';
						$value .= $m.'min';
					} break;
				}
				if(($p_prop['emptyifzero'] ?? false) && floatval($value) == 0)
					$value = '';
				if(($p_prop['delsegmentifzero'] ?? false) && floatval($value) == 0)
					return('');
				if(!is_string($value))
					$value = trim($value);
				if(($p_prop['newline'] ?? false) && $value != '')
				{
					if(str_contains($p_prop['newline'], 'before'))
						$value = chr(10).$value;
					if(str_contains($p_prop['newline'], 'after'))
						$value = $value.chr(10);
				}
				if(!is_array($value))
					$result .= $this->odt_entities($value);
			}
		}
		return $result;
	}

	function replace_placeholders(&$params)
	{
		$result = '';
		foreach($this->break_into_segments($this->content_xml) as $seg)
		{
			if($seg['type'] == 'list')
			{
				if(isset($params[$seg['fields']]) && is_array($params[$seg['fields']])) {
					foreach($params[$seg['fields']] as $item)
					{
						$np = $params;
						foreach($item as $k => $v)
						{
							$np['item.'.$k] = $v;
							$params['sums.'.$seg['fields'].'.'.$k] += floatval($v);
						}
						$result .= $this->run_segment($seg, $np);
					}
				}
			}
			else
			{
				$result .= $this->run_segment($seg, $params);
			}
		}
		return $this->content_xml = $result;
	}

	function create_output($out_filename)
	{
		$this->odt_filename = $out_filename;
		$content_file = $this->temp_dir . '/content.xml';
		if (file_put_contents($content_file, $this->content_xml) === false) {
			$this->error_msg = 'Failed to write content.xml';
			return false; //
		}

		$this->debug_output = trim(
			shell_exec('cd '.escapeshellarg($this->temp_dir).' ; zip -r ../tmp.odt * 2>&1'));
		if(!file_exists('/tmp/tmp.odt'))
		{
			$this->error_msg = 'Failed to create tmp ODT file (shell "'.$this->debug_output.'")';
			return false;
		}
		if(file_exists($out_filename)) {
			unlink($out_filename);
		}
		shell_exec('mv /tmp/tmp.odt '.escapeshellarg($out_filename));

		if (!file_exists($out_filename)) {
			$this->error_msg = 'Failed to create ODT file "'.$out_filename.'"';
			return false; //
		}

		$this->pdf_filename = $pdf_output = pathinfo($out_filename, PATHINFO_DIRNAME) . '/' . pathinfo($out_filename, PATHINFO_FILENAME) . '.pdf';
		$command = 'HOME=/tmp ; soffice --headless --convert-to pdf ' . escapeshellarg($out_filename) . ' --outdir ' . escapeshellarg(pathinfo($out_filename, PATHINFO_DIRNAME)) . ' 2>&1';
		$this->debug_output = shell_exec($command);

		if (!file_exists($pdf_output)) {
			$this->error_msg = 'PDF conversion failed';
			return false; //
		}

		return $pdf_output;
	}

	function __destruct()
	{
		$this->delete_directory($this->temp_dir);
	}

	function delete_directory($dir)
	{
		if (!is_dir($dir)) return;
		$items = array_diff(scandir($dir), ['.', '..']);
		foreach ($items as $item) {
			$path = "$dir/$item";
			is_dir($path) ? $this->delete_directory($path) : unlink($path);
		}
		rmdir($dir);
	}

}
