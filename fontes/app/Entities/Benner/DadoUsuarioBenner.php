<?php

namespace App\Entities\Benner;

use Illuminate\Database\Eloquent\Model;
use App\Entities\Benner\Funcionario;
use Modules\Usuario\Entities\Usuario;

class DadoUsuarioBenner extends Model
{

    protected $table = 'prh.tb_dado_usuario_benner';
    protected $primaryKey = 'sq_dado_usuario_benner';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'cd_usuario_benner',
        'nr_matricula',
        'nr_matricula_gestor_benner',
        'no_gestor_benner',
        'cd_area_benner',
        'ds_area_benner',
        'cd_cargo_benner',
        'ds_cargo_benner',
        'cd_funcao_benner',
        'ds_funcao_benner',
        'ds_sexo_benner',
        'dt_admissao_benner',
        'cd_area_primaria_benner',
        'ds_area_primaria_benner',
    ];

    private function consultarDadoAtualizado($nrMatricula)
    {
        return $this->where('nr_matricula' ,$nrMatricula)
            ->where('st_registro_ativo', 'S')
            ->whereRaw("trunc(dt_inclusao) = trunc(sysdate)")
            ->getModels();
    }

    public function empregadoUsuario()
    {
        return $this->belongsTo(Usuario::class, 'nr_matricula', 'nr_matricula');
    }

    public function consultar($nrMatricula)
    {
        $dadosUsuarioAtivo = $this->consultarDadoAtualizado($nrMatricula);

        if($dadosUsuarioAtivo == null){
            $this->salvar($nrMatricula);
        }

        return $this->consultarDadoAtualizado($nrMatricula);

    }

    public function verifica_dados_usuario()
    {
        set_time_limit(3000);

        $usuarios_ativos = DadoUsuarioBenner::where('st_registro_ativo', '=', 'S')->get();
        $funcionario = new Funcionario();

        foreach ($usuarios_ativos as $key => $usuario) {
            $dadosFuncionario = $funcionario->consultaDadosFuncionarioRotina($usuario->nr_matricula);
            if($dadosFuncionario){
                $this->atualiza_dados_usuario($dadosFuncionario, $usuario);
            }
        }

    }

    public function atualiza_dados_usuario($dadosFuncionario, $usuario)
    {
        if($dadosFuncionario[0]->cd_cargo != $usuario->cd_cargo_benner ||  $dadosFuncionario[0]->cd_funcao != $usuario->cd_funcao_benner || ltrim($dadosFuncionario[0]->aod, "0") != $usuario->cd_area_benner)
        {
            $this->inativarDados($usuario->nr_matricula);

            $data = [
                'cd_usuario_benner'             => $dadosFuncionario[0]->handle,
                'nr_matricula'                  => $usuario->nr_matricula,
                'nr_matricula_gestor_benner'    => $dadosFuncionario[0]->gestor,
                'no_gestor_benner'              => $dadosFuncionario[0]->nome_gestor,
                'cd_area_benner'                => ltrim($dadosFuncionario[0]->aod, "0"),
                'cd_area_primaria_benner'       => ltrim($dadosFuncionario[0]->hierarquia_primaria_aod, "0"),
                'ds_area_benner'                => $dadosFuncionario[0]->lotacao,
                'ds_area_primaria_benner'       => $dadosFuncionario[0]->hierarquia_primaria,
                'cd_cargo_benner'               => $dadosFuncionario[0]->cd_cargo,
                'ds_cargo_benner'               => $dadosFuncionario[0]->ds_cargo,
                'cd_funcao_benner'              => $dadosFuncionario[0]->cd_funcao,
                'ds_funcao_benner'              => $dadosFuncionario[0]->ds_funcao,
                'ds_sexo_benner'                => $dadosFuncionario[0]->sexo,
                'dt_admissao_benner'            => $dadosFuncionario[0]->dataadmissao,
            ];

            $this::create($data);

        }
        return;

    }

    public function salvar($nrMatricula)
    {
        $this->inativarDados($nrMatricula);

        $funcionario = new Funcionario();
        $dadosFuncionario = $funcionario->consultaDadosFuncionario($nrMatricula);

            if($dadosFuncionario){
                $data = [
                    'cd_usuario_benner'             => $dadosFuncionario[0]->handle,
                    'nr_matricula'                  => $nrMatricula,
                    'nr_matricula_gestor_benner'    => $dadosFuncionario[0]->gestor,
                    'no_gestor_benner'              => $dadosFuncionario[0]->nome_gestor,
                    'cd_area_benner'                => ltrim($dadosFuncionario[0]->aod, "0"),
                    'cd_area_primaria_benner'       => ltrim($dadosFuncionario[0]->hierarquia_primaria_aod, "0"),
                    'ds_area_benner'                => $dadosFuncionario[0]->lotacao,
                    'ds_area_primaria_benner'       => $dadosFuncionario[0]->hierarquia_primaria,
                    'cd_cargo_benner'               => $dadosFuncionario[0]->cd_cargo,
                    'ds_cargo_benner'               => $dadosFuncionario[0]->ds_cargo,
                    'cd_funcao_benner'              => $dadosFuncionario[0]->cd_funcao,
                    'ds_funcao_benner'              => $dadosFuncionario[0]->ds_funcao,
                    'ds_sexo_benner'                => $dadosFuncionario[0]->sexo,
                    'dt_admissao_benner'            => $dadosFuncionario[0]->dataadmissao,
                ];

                $save = $this::create($data);
            }
        return;

    }

    public function inativarDados($nrMatricula)
    {
        $dadosUsuarioAtivo = $this->where('nr_matricula',$nrMatricula)->getModels();

        foreach ($dadosUsuarioAtivo as $dados) {
            $dados->st_registro_ativo = 'N';
            $dados->save();
        }
    }

}

