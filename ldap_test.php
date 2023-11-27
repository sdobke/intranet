<?PHP
// using ldap bind
$ldapsrv  = '172.31.1.16:389';
$ldaprdn  = 'rhirsch@alsea-netodp.local';     // ldap rdn or dn
$ldappass = 'asd';  // associated password
// connect to ldap server
$ldapconn = ldap_connect($ldapsrv)
    or die("Could not connect to LDAP server.");
if ($ldapconn) {
    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);
    // verify binding
    if ($ldapbind) {
        echo "OK";
    } else {
        echo "Error";
    }
}
?>
