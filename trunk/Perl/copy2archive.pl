#!/usr/bin/perl -w

# /usr/bin/perl -I/var/www/vhosts/megsm.de/subdomains/webcam/cgi-bin /var/www/vhosts/megsm.de/subdomains/webcam/cgi-bin/copy2archive.pl rt_limit=290 >/dev/null 2>&1
use strict;
use lib "/cgi-bin";
use CGI::Carp 'fatalsToBrowser';
use File::Copy;
use Image::Magick;
use copy2_utils;
use copy2_config;
use Time::Local;
use File::Basename;

my ($webcam, $mtime, $mtime1);
my ($archive_file, $upload_file, $current, $thumbnail_file, $old, $new, $w, $h);

my ($path, $archive_path, $thumbnail_path, $today_path);
my ($rt_stop, $rt_start, $rt_limit, $tme);

my ($pattern1, $i, $year, $month, $day, $hour, $minute, $second, $fname);
my (@files);


&getparams;

$rt_limit    = $in{rt_limit};
if (!defined($rt_limit)) {$rt_limit = ""};

$rt_start    = time;

if ($rt_limit eq "")
{
    # dont run as service:
    $rt_limit = 0;
    # switch output messages on:
    $log_level = 1;
}
else
{
    # switch output messages off:
    $log_level = 0;
}
$rt_stop     = $rt_start + $rt_limit;


&message (1, "Content-Type: text/html\n\n");
&message (1, "Copy2Archive, Version $version<br>\n");


do
{
    $i = 0;
    foreach $path (@webcams)
    {
        $archive_path   = $abs_path."/".$path."/archive/";
        $today_path     = $archive_path.&format_time('%Y-%m-%d',time)."/";
        $thumbnail_path = $today_path.$thumbnail_width."x".$thumbnail_height."/";
        $upload_file = $abs_path."/".$path."/current.jpg";
        $current = $upload_file;        
       


        if ($mode[$i] == 0)
        {
            $pattern1 = $abs_path."/".$path."/".&format_time('%Y%m%d',time)."*.jpg";
            @files  = glob("$pattern1");
            if (-s $files[0])
            {
                $upload_file = $files[0];
                $fname = basename($upload_file);
                $mtime = (stat($upload_file))[9];
                $year =   substr($fname, 0, 4);
                $month =  substr($fname, 4, 2) - 1;
                $day =    substr($fname, 6, 2);
                $hour =   substr($fname, 8, 2);
                $minute = substr($fname, 10, 2);
                $second = substr($fname, 12, 2);
                if ($month > 12) {$month = 11};
                if ($month < 0) {$month = 0};
                
                $mtime1 = timelocal($second,$minute,$hour,$day,$month,$year); 
            }
        }
        elsif ($mode[$i] == 1)
        {
            if (-s $current)
            {
                $mtime = (stat($current))[9];
                $mtime1 = $mtime;
            }
        }
        else
        {
            $mtime = 0;
            $mtime1 = $mtime;
        }
        
    
        $archive_file   = $today_path.$mtime1.".jpg";
        $thumbnail_file = $thumbnail_path.$mtime1.".jpg";
        
      
    
        if (-s $archive_file)
        {
            &message ($log_level, "$upload_file exists in archive<br>\n");
        }
        else
        {
            if (-s $upload_file)
            {
                # no action before file is uploaded completely:
                if (time - $mtime > 15 && time - $mtime < 3600)
                {
                    &make_working_directory($archive_path);
                    &make_working_directory($today_path);
                    &make_working_directory($thumbnail_path);
                    
                    &delete_old_dirs($i, $archive_path);
                    
                    &message ($log_level, "copy ($upload_file, $archive_file)<br>\n");
                    copy ($upload_file, $archive_file);
                   
                    chmod (0755, $archive_file);
                    $old = Image::Magick->new;
                    $old->Read($upload_file);
                    $new = $old->Clone;
                    $new->Scale(width=>$thumbnail_width, height=>$thumbnail_height);
                    $new->Write($thumbnail_file);
                    chmod (0755, $thumbnail_file);
                    
                    if ($mode[$i] == 0)
                    {
                        &message ($log_level, "copy ($upload_file, $current)<br>\n");
                        copy ($upload_file, $current);
                        unlink ($upload_file);
                    }
                    
                }
            }
        }
        $i++;
    }
    if ($rt_limit > 0) {sleep(5)};

} until (time >= $rt_stop);
&message (1, "exit<br>\n");


