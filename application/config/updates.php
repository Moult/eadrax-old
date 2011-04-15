<?php
/**
 * Eadrax
 *
 * Eadrax is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * Eadrax is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *                                                                                
 * You should have received a copy of the GNU General Public License
 * along with Eadrax; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @category	Eadrax
 * @package		Update
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

// Set the path to the ffmpeg application binary.
$config['ffmpeg_path'] = '/usr/bin/ffmpeg';

// Set the path to the diff application binary.
$config['diff_path'] = '/usr/bin/diff';

// The upload size limit for users per update.
$config['user_upload_limit'] = '50M';

// The upload size limit for guests per update.
$config['guest_upload_limit'] = '5M';

// The layout width that update previews cannot be bigger than.
$config['fit_width'] = 830;

// The layout height that update previews cannot be bigger than.
$config['fit_height'] = 600;

// The allowed filetypes for users on the website.
$config['filetypes'] = 'gif,jpg,jpeg,png,svg,tiff,bmp,exr,pdf,zip,rar,tar,gz,bz,7z,ogg,wmv,mp3,wav,avi,mpg,mov,swf,flv,blend,xcf,doc,ppt,xls,odt,ods,odp,odg,psd,fla,ai,indd,aep,txt,cab,csv,exe,diff,patch,rtf,torrent,mp4';

// Languages to support for syntax highlighting.
// Note: these must be supported by GeSHi.
$config['languages'] = array(
	'0' => 'None',
	'abap' => 'ABAP',
	'actionscript' => 'ActionScript',
	'actionscript3' => 'ActionScript 3',
	'ada' => 'Ada',
	'apache' => 'Apache configuration',
	'applescript' => 'AppleScript',
	'apt_sources' => 'Apt sources',
	'asm' => 'ASM',
	'asp' => 'ASP',
	'autoit' => 'AutoIt',
	'avisynth' => 'AviSynth',
	'bash' => 'Bash',
	'basic4gl' => 'Basic4GL',
	'bf' => 'Brainfuck',
	'blitzbasic' => 'BlitzBasic',
	'bnf' => 'bnf',
	'boo' => 'Boo',
	'c' => 'C',
	'c_mac' => 'C (Mac)',
	'caddcl' => 'CAD DCL',
	'cadlisp' => 'CAD Lisp',
	'cfdg' => 'CFDG',
	'cfm' => 'ColdFusion',
	'cil' => 'CIL',
	'cobol' => 'COBOL',
	'cpp' => 'C++',
	'cpp-qt" class="sublang' => '&nbsp;&nbsp;C++ (QT)',
	'csharp' => 'C#',
	'css' => 'CSS',
	'd' => 'D',
	'dcs' => 'DCS',
	'delphi' => 'Delphi',
	'diff' => 'Diff',
	'div' => 'DIV',
	'dos' => 'DOS',
	'dot' => 'dot',
	'eiffel' => 'Eiffel',
	'email' => 'eMail (mbox)',
	'fortran' => 'Fortran',
	'freebasic' => 'FreeBasic',
	'genero' => 'genero',
	'gettext' => 'GNU Gettext',
	'glsl' => 'glSlang',
	'gml' => 'GML',
	'gnuplot' => 'Gnuplot',
	'groovy' => 'Groovy',
	'haskell' => 'Haskell',
	'hq9plus' => 'HQ9+',
	'html4strict' => 'HTML',
	'idl' => 'Uno Idl',
	'ini' => 'INI',
	'inno' => 'Inno',
	'intercal' => 'INTERCAL',
	'io' => 'Io',
	'java' => 'Java',
	'java5' => 'Java(TM) 2 Platform Standard Edition 5.0',
	'javascript' => 'Javascript',
	'kixtart' => 'KiXtart',
	'klonec' => 'KLone C',
	'klonecpp' => 'KLone C++',
	'latex' => 'LaTeX',
	'lisp' => 'Lisp',
	'lolcode' => 'LOLcode',
	'lotusformulas' => 'Lotus Notes @Formulas',
	'lotusscript' => 'LotusScript',
	'lscript' => 'LScript',
	'lua' => 'Lua',
	'm68k' => 'Motorola 68000 Assembler',
	'make' => 'GNU make',
	'matlab' => 'Matlab M',
	'mirc' => 'mIRC Scripting',
	'modula3' => 'Modula-3',
	'mpasm' => 'Microchip Assembler',
	'mxml' => 'MXML',
	'mysql' => 'MySQL',
	'nsis' => 'NSIS',
	'objc' => 'Objective-C',
	'ocaml' => 'OCaml',
	'ocaml-brief" class="sublang' => '&nbsp;&nbsp;OCaml (brief)',
	'oobas' => 'OpenOffice.org Basic',
	'oracle11' => 'Oracle 11 SQL',
	'oracle8' => 'Oracle 8 SQL',
	'pascal' => 'Pascal',
	'per' => 'per',
	'perl' => 'Perl',
	'php' => 'PHP',
	'php-brief" class="sublang' => '&nbsp;&nbsp;PHP (brief)',
	'pic16' => 'PIC16',
	'pixelbender' => 'Pixel Bender 1.0',
	'plsql' => 'PL/SQL',
	'povray' => 'POVRAY',
	'powershell' => 'posh',
	'progress' => 'Progress',
	'prolog' => 'Prolog',
	'providex' => 'ProvideX',
	'python' => 'Python',
	'qbasic' => 'QBasic/QuickBASIC',
	'qbasic' => 'QBasic/QuickBASIC',
	'rails' => 'Rails',
	'rebol' => 'REBOL',
	'reg' => 'Microsoft Registry',
	'robots' => 'robots.txt',
	'ruby' => 'Ruby',
	'sas' => 'SAS',
	'scala' => 'Scala',
	'scheme' => 'Scheme',
	'scilab' => 'SciLab',
	'sdlbasic' => 'sdlBasic',
	'smalltalk' => 'Smalltalk',
	'smarty' => 'Smarty',
	'sql' => 'SQL',
	'tcl' => 'TCL',
	'teraterm' => 'Tera Term Macro',
	'text' => 'Text',
	'thinbasic' => 'thinBasic',
	'tsql' => 'T-SQL',
	'typoscript' => 'TypoScript',
	'vb' => 'Visual Basic',
	'vbnet' => 'vb.net',
	'verilog' => 'Verilog',
	'vhdl' => 'VHDL',
	'vim' => 'Vim Script',
	'visualfoxpro' => 'Visual Fox Pro',
	'visualprolog' => 'Visual Prolog',
	'whitespace' => 'Whitespace',
	'whois' => 'Whois Response',
	'winbatch' => 'Winbatch',
	'xml' => 'XML',
	'xorg_conf' => 'Xorg configuration',
	'xpp' => 'X++',
	'z80' => 'ZiLOG Z80 Assembler'
);
