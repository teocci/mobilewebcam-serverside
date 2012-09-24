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
$thumbnail_width $thumbnail_height $archive_size
@webcams
);
our $version = "1.01";

# set log_level to 0, if you run as cron or if you want to suppress output:
our $log_level = 1;

# set number of seconds, which will be kept in archive; day has 86400 seconds:
our $archive_size = 31*24*3600;

# define size of thumbnails here:
our $thumbnail_width=320;
our $thumbnail_height=240;

# define the name of the root path of each webcam on this domain:
our (@webcams);
@webcams = ("webcam1", "webcam2", "webcam3");


our ($abs_path);
$abs_path       = $ENV{'DOCUMENT_ROOT'};
if (!defined($abs_path)) {$abs_path = $dir;}
1;
