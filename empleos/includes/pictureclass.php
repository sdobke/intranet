<?PHP
	class bildmail
	{
		var $boundary;
		var $bildboundary;
		var $bildID;
		var $BildHeader;
		var $textboundary;
		var $emailheader = "";
		var $empfaenger;
		var $betreff;
		var $textheader;
		
		function bildmail()
		{
			$this->boundary = uniqid(time());
			$this->bildboundary = uniqid(time());
			$this->bildID = uniqid(time());
			$this->textboundary = uniqid(time());
		}

		function from($name,$email)	
		{
			$this->emailheader .= "From: $name<$email>\n";
			$this->emailheader .= "MIME-Version: 1.0\n";
		}
		
		function to($to,$to_nombre)
		{
			$this->empfaenger = $to_nombre.'<'.$to.'>';
		}
		function subject($subject)
		{
			$this->betreff = $subject;
		}
		
		function settext($text)
		{
			$this->textheader .= "Content-Type: multipart/alternative; boundary=\"$this->textboundary\"\n\n";
			$this->textheader .= "--$this->textboundary\n";
			$this->textheader .= "Content-Type: text/plain; charset=\"ISO-8859-1\"\n";
			$this->textheader .= "Content-Transfer-Enconding: 7bit\n\n";
			$this->textheader .= strip_tags($text)."\n\n";
			$this->textheader .= "--$this->textboundary\n";
			$this->textheader .= "Content-Type: text/html; charset=\"ISO-8859-1\"\n";
			$this->textheader .= "Content-Transfer-Enconding: 7bit\n\n";
			$this->textheader .= "<html><body>$text</body></html>\n\n";
			$this->textheader .= "--$this->textboundary--\n\n";
		}		
		
		function setbild($Dateiname)
		{
			if(is_file($Dateiname))
			{
				$showbildtyp = substr($Dateiname,-3);

				if($showbildtyp == "jpg" || $showbildtyp == "gif" || $showbildtyp == "png")
				{
					$imagetyp = array("jpg" => "jpeg", "gif" => "gif", "png" => "png");

					$bilddaten = chunk_split(base64_encode(fread(fopen($Dateiname,"rb"),filesize($Dateiname))),72);

					$header = "--$this->boundary\n";
					$header .= "Content-Type: image/".$imagetyp[$showbildtyp].";\n name=\"$Dateiname\"\n";
					$header .= "Content-Transfer-Encoding: base64\n";
					$header .= "Content-ID: <$this->bildID>\n\n";
					$header .= $bilddaten."\n\n";
			
					$this->BildHeader[] = $header;
			
					return "<img src=\"cid:$this->bildID\">";
				}
				else
				{
					echo "Error en el formato de la imagen...";
				}
			}
		}
		
		function send()	
		{
			$header = $this->emailheader;
			
			if(count($this->BildHeader)>0)
			{
				$header .= 	"Content-Type: multipart/related; type=\"multipart/alternative\"; boundary=\"$this->boundary\"\n\n";
				$header .= "--$this->boundary\n";
				$header .= $this->textheader;
				
				for($i=0;$i<count($this->BildHeader);$i++)
				{
					$header .= $this->BildHeader[$i];
				}
				
				$header .= "--$this->boundary--";
			}
			else
			{
				$header .= $this->textheader;
			}
			// Envia el mail
			mail("$this->empfaenger",$this->betreff,"",$header);
		}
	}
?>