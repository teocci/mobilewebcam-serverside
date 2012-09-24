package copy2_utils;
use strict;
use warnings;

use CGI;
use File::Copy;
use File::Path;
use Time::Local;

use copy2_config;

require Exporter;
our @ISA = qw(Exporter);
our @EXPORT = qw(
make_working_directory message getparams format_time delete_old_dirs
%in
);

our %in;

sub make_working_directory
{
   my ($path) = @_;
   unless (-d "$path")
   {
      mkpath($path) ;
      chmod (0777, $path);
   }
   (-w "$path") or die "can't write to directory $path $!";
}
sub message
{
    my ($modus, $line) =@_;
    if ($modus == 1)
    {
        print $line;
    }
}
sub getparams
{
    my ($query);
    $query = new CGI;
    my $cgi = CGI->new();
    %in = $cgi->Vars();
    $query->delete_all();
}
sub format_time {
  
  my ($format, $tme) =@_;


  my $y=sprintf("%02d",(localtime($tme))[5]-100);
  my $Y=sprintf("%04d",(localtime($tme))[5]+1900);
  my $m=sprintf("%02d",(localtime($tme))[4]+1);
  my $d=sprintf("%02d",(localtime($tme))[3]);
  my $H=sprintf("%02d",(localtime($tme))[2]);
  my $M=sprintf("%02d",(localtime($tme))[1]);
  my $S=sprintf("%02d",(localtime($tme))[0]);

  $format =~ s/%y/$y/;
  $format =~ s/%Y/$Y/;
  $format =~ s/%m/$m/;
  $format =~ s/%d/$d/;
  $format =~ s/%H/$H/;
  $format =~ s/%M/$M/;
  $format =~ s/%S/$S/;

  return ($format);
}
sub delete_old_dirs
{
   my ($path) = @_;
   my ($file, $file1, $pattern, $mtime);
   my ($sec,$min,$hours,$day,$month,$year);
   my (@files);
   opendir(DIR,$path);
   while ($file = readdir(DIR))
   {
      if ($file =~ /\d{4}\-\d{2}\-\d{2}/)
      {
         ($year, $month, $day) = split(/\-/,$file);
         $mtime = timelocal(0,0,0,$day,$month-1,$year); 
         if (time - $mtime > $archive_size)
         {          
            $file1 = $path.$file;
            &message ($log_level, "delete $file1<br>\n");
            rmtree ($file1);
         }    
      }
   }
   closedir(DIR);
   
}


1;
