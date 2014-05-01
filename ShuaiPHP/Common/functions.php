<?php

// ������Ѻõı������
function dump($var, $echo=true, $label=null, $strict=true) {
	$label = ($label === null) ? '' : rtrim($label) . ' ';
	if (!$strict) {
		if (ini_get('html_errors')) {
			$output = print_r($var, true);
			$output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
		} else {
			$output = $label . print_r($var, true);
		}
	} else {
		ob_start();
		var_dump($var);
		$output = ob_get_clean();
		if (!extension_loaded('xdebug')) {
			$output = preg_replace("/\]\=\>\n(\s+)/m", '] => ', $output);
			$output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
		}
	}
	if ($echo) {
		echo($output);
		return null;
	}else
		return $output;
}

// URL�ض���
function redirect($url, $time=0, $msg='') {
	//����URL��ַ֧��
	$url = str_replace(array("\n", "\r"), '', $url);
	if (empty($msg))
		$msg = "ϵͳ����{$time}��֮���Զ���ת��{$url}��";
	if (!headers_sent()) {
		// redirect
		if (0 === $time) {
			header('Location: ' . $url);
		} else {
			header("refresh:{$time};url={$url}");
			echo($msg);
		}
		exit();
	} else {
		$str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
		if ($time != 0)
			$str .= $msg;
		exit($str);
	}
}

// �����ļ����ݶ�ȡ�ͱ��� ��Լ��������� �ַ���������
function F($name, $value='', $path=DATA_PATH) {
	static $_cache = array();
	$filename = $path . $name . '.php';
	if ('' !== $value) {
		if (is_null($value)) {
			// ɾ������
			return unlink($filename);
		} else {
			// ��������
			$dir = dirname($filename);
			// Ŀ¼�������򴴽�
			if (!is_dir($dir))
				mkdir($dir);
			$_cache[$name] =   $value;
			return file_put_contents($filename, strip_whitespace("<?php\nreturn " . var_export($value, true) . ";\n?>"));
		}
	}
	if (isset($_cache[$name]))
		return $_cache[$name];
	// ��ȡ��������
	if (is_file($filename)) {
		$value = include $filename;
		$_cache[$name] = $value;
	} else {
		$value = false;
	}
	return $value;
}
