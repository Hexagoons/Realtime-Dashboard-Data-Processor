<?php
print "<?nl.hexagoons.weatherdata.xml version=\"1.0\"?>\n";
print "<WEATHERDATA>\n";

for ($i = 0; $i < count($measurements); $i++) {
    print "\t<MEASUREMENT>\n";
        print "\t\t<STN>{$measurements[$i]['stn']}</STN>\n";
        print "\t\t<DATE>{$measurements[$i]['date']}</DATE>\n";
        print "\t\t<TIME>{$measurements[$i]['time']}</TIME>\n";
        print "\t\t<TEMP>{$measurements[$i]['temp']}</TEMP>\n";
        print "\t\t<WDSP>{$measurements[$i]['wdsp']}</WDSP>\n";
        print "\t\t<SNDP>{$measurements[$i]['sndp']}</SNDP>\n";
        print "\t\t<ICE>{$measurements[$i]['ice']}</ICE>\n";
        print "\t\t<SNOW>{$measurements[$i]['snow']}</SNOW>\n";
        print "\t\t<TRND>{$measurements[$i]['tornado']}</TRND>\n";
    print "\t</MEASUREMENT>\n";
}

print "</WEATHERDATA>\n";