<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Paste extends Data\Paste implements Proposal
{
    public $project;
    public $private;
    public $text;
    public $syntax;
    private $repository;
    private $validator;

    public function __construct(Data\Paste $paste, Repository $repository, Tool\Validator $validator)
    {
        $this->project = $paste->project;
        $this->private = $paste->private;
        $this->text = $paste->text;
        $this->syntax = $paste->syntax;
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function validate()
    {
        $this->validator->setup(array(
            'text' => $this->text,
            'syntax' => $this->syntax
        ));
        $this->validator->rule('content', 'not_empty');
        $this->validator->rule('syntax', 'not_empty');
        $this->validator->callback('syntax', array($this, 'validate_syntax'), array('syntax'));
        if ( ! $this->validator->check())
            throw new Exception\Validation($this->validator->errors());
    }

    public function validate_syntax($syntax)
    {
        $supported_syntaxes = array('none', 'abap', 'actionscript', 'actionscript3', 'ada', 'apache', 'applescript', 'apt_sources', 'asm', 'asp', 'autoit', 'avisynth', 'bash', 'basic4gl', 'bf', 'blitzbasic', 'bnf', 'boo', 'c', 'c_mac', 'caddcl', 'cadlisp', 'cfdg', 'cfm', 'cil', 'cobol', 'cpp', 'cpp-qt', 'csharp', 'css', 'd', 'dcs', 'delphi', 'diff', 'div', 'dos', 'dot', 'eiffel', 'email', 'fortran', 'freebasic', 'genero', 'gettext', 'glsl', 'gml', 'gnuplot', 'groovy', 'haskell', 'hq9plus', 'html4strict', 'idl', 'ini', 'inno', 'intercal', 'io', 'java', 'java5', 'javascript', 'kixtart', 'klonec', 'klonecpp', 'latex', 'lisp', 'lolcode', 'lotusformulas', 'lotusscript', 'lscript', 'lua', 'm68k', 'make', 'matlab', 'mirc', 'modula3', 'mpasm', 'mxml', 'mysql', 'nsis', 'objc', 'ocaml', 'ocaml-brief', 'oobas', 'oracle11', 'oracle8', 'pascal', 'per', 'perl', 'php', 'php-brief', 'pic16', 'pixelbender', 'plsql', 'povray', 'powershell', 'progress', 'prolog', 'providex', 'python', 'qbasic', 'rails', 'rebol', 'reg', 'robots', 'ruby', 'sas', 'scala', 'scheme', 'scilab', 'sdlbasic', 'smalltalk', 'smarty', 'sql', 'tcl', 'teraterm', 'text', 'thinbasic', 'tsql', 'typoscript', 'vb', 'vbnet', 'verilog', 'vhdl', 'vim', 'visualfoxpro', 'visualprolog', 'whitespace', 'whois', 'winbatch', 'xml', 'xorg_conf', 'xpp', 'z80');

        return (bool) in_array($syntax, $supported_syntaxes);
    }

    public function submit()
    {
        $this->id = $this->repository->save_paste(
            $this->project->id,
            $this->private,
            $this->text,
            $this->syntax
        );
    }

    public function get_id()
    {
        return $this->id;
    }
}
