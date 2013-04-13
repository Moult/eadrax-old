<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Proposal extends Data\Update
{
    private $validation;

    public function __construct(Data\Update $update, Tool\Validation $validation)
    {
        $this->type = $update->type;
        $this->content = $update->content;
        $this->extra = $update->extra;
        $this->private = $update->private;
        $this->project = $update->project;

        $this->validation = $validation;
    }

    public function validate()
    {
        if ($this->type === 'text')
        {
            $this->validate_text();
        }
        elseif ($this->type === 'paste')
        {
            $this->validate_paste();
        }
        elseif ($this->type === 'website')
        {
            $this->validate_website();
        }
        elseif ($this->type === 'file')
        {
            $this->validate_file();
        }

        if ( ! $this->validation->check())
            throw new Exception\Validation($this->validation->errors());
    }

    private function validate_text()
    {
        $this->validation->setup(array('content' => $this->content));
        $this->validation->rule('content', 'not_empty');
        $this->extra = NULL;
    }

    private function validate_paste()
    {
        $this->validation->setup(array(
            'content' => $this->content,
            'syntax' => $this->extra
        ));
        $this->validation->rule('content', 'not_empty');
        $this->validation->rule('syntax', 'not_empty');
        $this->validation->callback('syntax', array($this, 'validate_paste_syntax'), array('syntax'));
    }

    public function validate_paste_syntax($syntax)
    {
        $supported_syntaxes = array('none', 'abap', 'actionscript', 'actionscript3', 'ada', 'apache', 'applescript', 'apt_sources', 'asm', 'asp', 'autoit', 'avisynth', 'bash', 'basic4gl', 'bf', 'blitzbasic', 'bnf', 'boo', 'c', 'c_mac', 'caddcl', 'cadlisp', 'cfdg', 'cfm', 'cil', 'cobol', 'cpp', 'cpp-qt', 'csharp', 'css', 'd', 'dcs', 'delphi', 'diff', 'div', 'dos', 'dot', 'eiffel', 'email', 'fortran', 'freebasic', 'genero', 'gettext', 'glsl', 'gml', 'gnuplot', 'groovy', 'haskell', 'hq9plus', 'html4strict', 'idl', 'ini', 'inno', 'intercal', 'io', 'java', 'java5', 'javascript', 'kixtart', 'klonec', 'klonecpp', 'latex', 'lisp', 'lolcode', 'lotusformulas', 'lotusscript', 'lscript', 'lua', 'm68k', 'make', 'matlab', 'mirc', 'modula3', 'mpasm', 'mxml', 'mysql', 'nsis', 'objc', 'ocaml', 'ocaml-brief', 'oobas', 'oracle11', 'oracle8', 'pascal', 'per', 'perl', 'php', 'php-brief', 'pic16', 'pixelbender', 'plsql', 'povray', 'powershell', 'progress', 'prolog', 'providex', 'python', 'qbasic', 'rails', 'rebol', 'reg', 'robots', 'ruby', 'sas', 'scala', 'scheme', 'scilab', 'sdlbasic', 'smalltalk', 'smarty', 'sql', 'tcl', 'teraterm', 'text', 'thinbasic', 'tsql', 'typoscript', 'vb', 'vbnet', 'verilog', 'vhdl', 'vim', 'visualfoxpro', 'visualprolog', 'whitespace', 'whois', 'winbatch', 'xml', 'xorg_conf', 'xpp', 'z80');

        if (in_array($syntax, $supported_syntaxes))
            return TRUE;
        else
            return FALSE;
    }

    private function validate_website()
    {
        $this->validation->setup(array('content' => $this->normalise_url($this->content)));
        $this->validation->rule('content', 'not_empty');
        $this->validation->rule('content', 'url');
        $this->validation->rule('content', 'url_domain');
    }

    private function normalise_url($url)
    {
        return ((substr($url, 0, 7) !== 'http://') ? 'http://' : '').$url;
    }

    private function validate_file()
    {

        $supported_filetypes = array('gif', 'jpg', 'jpeg', 'png', 'svg', 'tiff', 'bmp', 'exr', 'pdf', 'zip', 'rar', 'tar', 'gz', 'bz', '7z', 'ogg', 'ogv', 'wmv', 'mp3', 'wav', 'avi', 'mpg', 'mpeg', 'mov', 'swf', 'flv', 'blend', 'xcf', 'doc', 'ppt', 'xls', 'odt', 'ods', 'odp', 'odg', 'psd', 'fla', 'ai', 'indd', 'aep', 'txt', 'cab', 'csv', 'exe', 'diff', 'patch', 'rtf', 'torrent', 'mp4');

        $this->validation->setup(array('content' => $this->content));
        $this->validation->rule('content', 'upload_valid');
        $this->validation->rule('content', 'upload_type', $supported_filetypes);
        $this->validation->rule('content', 'upload_size', '100M');
    }
}
