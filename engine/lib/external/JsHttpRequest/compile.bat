@echo off
perl -x -S "%0" %1 %2 %3 %4 %5 %6 %7 %8 %9
exit;
#!perl -w
#line 6
#########################################################################

my $debugDir = "debug";
my $miniDir = "mini";
my $orig = "$debugDir/JsHttpRequest.js";
my $minProg = "C:/Program Files/DojoMin/dojomin.bat";

my $source = readFile($orig);
my ($comment) = $source =~ m{^\s*(/\*.*?\*/)}s;
my %parts = reverse($source =~ m[(//\s*{{{ [ \t]* (\w*) .*? //[ \t]* }}})]sgx);
my $main = $parts{''}; delete $parts{''};

$parts{'script-xml'} = $parts{script} . "\n\n" . $parts{xml};

while (my ($k, $v) = each %parts) {
	my $fname = "JsHttpRequest-$k.js";
	my $newComment = $comment;
	$newComment =~ s/\*\s*\w+[^\r\n]*/$& ($k support only!)/s;
	writeFile($debugDir . '/' . $fname, $newComment . "\n" . $main . "\n\n" . $v);
	minify($debugDir . '/' . $fname, $miniDir . '/' . $fname);
}

minify($orig, "JsHttpRequest.js");
minify($orig, $miniDir . "/JsHttpRequest.js");




sub minify {
	my ($from, $to, $commentAdd) = @_;
	my ($comment) = readFile($from) =~ m{^\s*(/\*.*?\*/)}s;
	$comment =~ s/\*\s*\w+[^\r\n]*\s*\*/$& Minimized version: see debug directory for the complete one.\n */s;
	system("\"$minProg\" $from > $to");
	writeFile($to, $comment . "\n" . readFile($to));
}

sub readFile {
	my ($name) = @_;
	local $/;
	open(local *F, $name);
	return <F>;
}

sub writeFile {
	my ($name, $data) = @_;
	local $/;
	open(local *F, ">", $name);
	print F $data;
}