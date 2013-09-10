#########################################################################
#Assignment Number    1                                                 #
#Subject Code and Section: OOP344A                                      #
#Student Name:  MINGTAO LI                                              #
#Student Number: 016214108                                              #
#Instructor Name: Danny Abesdris                                        #
#Due Date: JUNE 17,2011                                                 #
#Date Submitted: JUNE 17,2011                                           #
#                                                                       #
#Student Oath:                                                          #
#All assignments must include the following statement:                  #
#                                                                       #
#"This assignment represents my own work in accordance                  #
#with Seneca Academic Policy"                                           #
#                                                                       #
#Signature: Mingtao Li                                                  #
#########################################################################


#########################################################################
#Student Assignment Submission Form                                     #
#==================================                                     #
#I/we declare that the attached assignment is wholly my own work        #
#in accordance with Seneca Academic Policy.  No part of this            #
#assignment has been copied manually or electronically from any         #
#other source (including web sites) or distributed to other students.   #
#                                                                       #
#Name(s)                                          
#   Mingtao Li                                                 
#########################################################################


#!usr/bin/perl

use warnings;

use strict;

my ($i,$k,$c,$s,$c1,$c2,$c3,$c4,$data,@data,@final,@gene,$numbers,$final,$ifiles,$ofiles,%code);

$c=$c1=$c2=$c3=$c4=0;

$ifiles = shift;

$ofiles = shift;

#open the input file and the output file

open(FD,$ifiles);

open(OUT,"> $ofiles");

%code = ("AAA","K","AAG","K","GAA","E","GAG","E",
	 "AAC","N","AAU","N","GAC","D","GAU","D",
	 "ACA","T","ACC","T","ACG","T","ACU","T",
	 "GCA","A","GCC","A","GCG","A","GCU","A",
	 "GGA","G","GGC","G","GGG","G","GGU","G",
	 "GUA","V","GUC","V","GUG","V","GUU","V",
	 "AUA","M","AUG","M","UAA","*","UAG","*",
	 "UGA","*","AUC","I","AUU","I","UAC","Y",
	 "UAU","Y","CAA","Q","CAG","Q","AGC","S",
	 "AGU","S","UCA","S","UCC","S","UCG","S",
	 "UCU","S","CAC","H","CAU","H","UGC","C",
	 "UGU","C","CCA","P","CCC","P","CCG","P",
	 "CCU","P","UGG","W","AGA","R","AGG","R",
	 "CGA","R","CGC","R","CGG","R","CGU","R",
	 "UUA","L","UUG","L","CUA","L","CUC","L",
	 "CUG","L","CUU","L","UUC","F","UUU","F");

#get all the data from the input file
$/ = undef;

$data = <FD>;

#only keep ATCGatcg characters in $data

$data =~ s/[^ATCGatcg]//g;

#make all uppercase
$data = uc($data);

#extract each 3 characters as a group, store in array @gene

@gene = $data =~ /\w{3}/g;

#join these array elements into a string $data,this will remove those unmatched characters

$data = join('',@gene);

#count number of 'A's in string $data
    
while ($data =~ /A/g) {

   $c1++;             }   

#count number of 'T's in string $data

while ($data =~ /T/g)  {

   $c2++;             }

#count number of 'C's in string $data

while ($data =~ /C/g)  {

   $c3++;               }

#count number of 'G's in string $data

while ($data =~ /G/g)   {

   $c4++;               }

#calculate good DNA's molecular weight

$c = $c1 * 135.13 + $c2 * 126.1 + $c3 * 111.1 + $c4 * 150.12;

#globally substitute A with U
$data =~ s/A/U/g;

#globally substitute T with A
$data =~ s/T/A/g;

#globally substitute C with Y,Y is a temp character which will be changed to G later
$data =~ s/C/Y/g;

#globally substitute G with C
$data =~ s/G/C/g;

#globally substitute Y with G
$data =~ s/Y/G/g;

#extract each 3 characters as a group, store in array @data

@data = $data =~ /\w{3}/g; 

#if there is a match to an element in hash variable %code,push the match's value in %code into array @final

foreach $s (@data) {       

  foreach (keys(%code)) {   

     if ( $s eq $_ ) {  

       push @final,$code{$s};     
     }
  }
}

#join these amino acid alias into string $final

$final = join('',@final);

#extract each 10 characters as a group, store in array @final

@final = $final =~ /[A-Z\*]{10}/g;

#get the number of elements in array @final

$numbers = @final;

#push the unmatched part in $final into array @final

push @final,substr($final,length($final)-length($final)%10);

#get the output lines number

$numbers = int ($numbers /6);

$i = 0;

#print the first number '1' into the output file
printf OUT "%10d",1;

for (@final) {

   $i++;

   #print the 10-amino-acid group into the output file
   printf OUT " %-s",$_;

   if ($i % 6 == 0 ) {                  

      #print the line numbering into the output file
      printf OUT "\n%10s",10*$i + 1; 
   }  
}

#change to new line in the output file
	   
printf OUT "\n";

#print the good DNA molecular weight into the output file

printf OUT $c;

#close the input file and the output file

close(FD);     

close(OUT);
