package copy2_config;
use strict;
use warnings;
use File::Basename;
use File::Spec;

my $path = File::Spec->rel2abs( $0 );
my $dir  = dirname( $path );
my $file = basename( $path );
$dir = substr($dir, 0, rindex($dir,'/'));

require Exporter;
our @ISA = qw(Exporter);
our @EXPORT = qw(
$version
$abs_path $log_level
$thumbnail_width $thumbnail_height
@webcams @archivesize
);
our $version = "1.02";

# set log_level to 0, if you run as cron or if you want to suppress output:
our $log_level = 1;

# define size of thumbnails here:
our $thumbnail_width=320;
our $thumbnail_height=240;

# define the name of the root path of each webcam on this domain:
our (@webcams);
@webcams = ("webcam1", "webcam2", "webcam3");

# define the size of archive; enter the number of days to be stored in archive;
# directories older than specified number of days will be deleted;
our (@archivesize);
@archivesize = (31,31,3);


our ($abs_path);
$abs_path       = $ENV{'DOCUMENT_ROOT'};
if (!defined($abs_path)) {$abs_path = $dir;}
1;
