<?php

declare(strict_types=1);

use App\Application\Actions\NotImplementedAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\Login\LoginAction;
use App\Application\Actions\Login\TestTokenAction;
use App\Application\Middleware\JwtMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Domain\Models\SystemSettings;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world !');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->post('/login/login', LoginAction::class);

    $key = 'Ajakep**#jj983';
    $app->post('/login/test-token', TestTokenAction::class)->add(new JwtMiddleware($key));

    $app->group('', function (Group $group) {
        // Usuários
        $group->get('/usuarios', \App\Application\Actions\Usuarios\ListarAction::class);
        $group->get('/usuarios/{id}', \App\Application\Actions\Usuarios\GetAction::class);
        $group->post('/usuarios', \App\Application\Actions\Usuarios\SaveAction::class);
        $group->put('/usuarios/{id}', \App\Application\Actions\Usuarios\UpdateAction::class);
        $group->delete('/usuarios/{id}', \App\Application\Actions\Usuarios\ExcluirAction::class);

        // Papéis e Permissões
        $group->get('/papeis', \App\Application\Actions\Papeis\ListarAction::class);
        $group->post('/papeis', \App\Application\Actions\Papeis\SaveAction::class);
        $group->get('/permissoes', \App\Application\Actions\Permissoes\ListarAction::class);
        $group->post('/permissoes', \App\Application\Actions\Permissoes\SaveAction::class);

        // Logs de Auditoria
        $group->get('/logs-auditoria', \App\Application\Actions\LogsAuditoria\ListarAction::class);
        $group->get('/logs-auditoria/{id}', \App\Application\Actions\LogsAuditoria\GetAction::class);

        // Pacientes
        $group->get('/pacientes', \App\Application\Actions\Pacientes\ListarAction::class);
        $group->get('/pacientes/{id}', \App\Application\Actions\Pacientes\GetAction::class);
        $group->post('/pacientes', \App\Application\Actions\Pacientes\SaveAction::class);
        $group->put('/pacientes/{id}', \App\Application\Actions\Pacientes\UpdateAction::class);
        $group->delete('/pacientes/{id}', \App\Application\Actions\Pacientes\ExcluirAction::class);
        $group->get('/pacientes/{id}/enderecos', \App\Application\Actions\Pacientes\ListarEnderecosAction::class);
        $group->post('/pacientes/{id}/enderecos', \App\Application\Actions\Pacientes\AdicionarEnderecoAction::class);

        // Consultas
        $group->get('/consultas', \App\Application\Actions\Consultas\ListarAction::class);
        $group->get('/consultas/{id}', \App\Application\Actions\Consultas\GetAction::class);
        $group->post('/consultas', \App\Application\Actions\Consultas\SaveAction::class);
        $group->put('/consultas/{id}', \App\Application\Actions\Consultas\UpdateAction::class);
        $group->delete('/consultas/{id}', \App\Application\Actions\Consultas\ExcluirAction::class);

        // Prontuários
        $group->get('/prontuarios', \App\Application\Actions\Prontuarios\ListarAction::class);
        $group->get('/prontuarios/{id}', \App\Application\Actions\Prontuarios\GetAction::class);
        $group->post('/prontuarios', \App\Application\Actions\Prontuarios\SaveAction::class);
        $group->put('/prontuarios/{id}', \App\Application\Actions\Prontuarios\UpdateAction::class);
        $group->delete('/prontuarios/{id}', \App\Application\Actions\Prontuarios\ExcluirAction::class);

        // Prescrições
        $group->get('/prescricoes', \App\Application\Actions\Prescricoes\ListarAction::class);
        $group->get('/prescricoes/{id}', \App\Application\Actions\Prescricoes\GetAction::class);
        $group->post('/prescricoes', \App\Application\Actions\Prescricoes\SaveAction::class);
        $group->put('/prescricoes/{id}', \App\Application\Actions\Prescricoes\UpdateAction::class);
        $group->delete('/prescricoes/{id}', \App\Application\Actions\Prescricoes\ExcluirAction::class);

        // Pedidos de Exame
        $group->get('/pedidos-exames', \App\Application\Actions\PedidosExames\ListarAction::class);
        $group->get('/pedidos-exames/{id}', \App\Application\Actions\PedidosExames\GetAction::class);
        $group->post('/pedidos-exames', \App\Application\Actions\PedidosExames\SaveAction::class);
        $group->put('/pedidos-exames/{id}', \App\Application\Actions\PedidosExames\UpdateAction::class);
        $group->delete('/pedidos-exames/{id}', \App\Application\Actions\PedidosExames\ExcluirAction::class);

        // Resultados de Exames
        $group->get('/resultados-exames', \App\Application\Actions\ResultadosExames\ListarAction::class);
        $group->get('/resultados-exames/{id}', \App\Application\Actions\ResultadosExames\GetAction::class);
        $group->post('/resultados-exames', \App\Application\Actions\ResultadosExames\SaveAction::class);

        // Profissionais
        $group->get('/profissionais', \App\Application\Actions\Profissionais\ListarAction::class);
        $group->get('/profissionais/{id}', \App\Application\Actions\Profissionais\GetAction::class);
        $group->post('/profissionais', \App\Application\Actions\Profissionais\SaveAction::class);
        $group->put('/profissionais/{id}', \App\Application\Actions\Profissionais\UpdateAction::class);
        $group->delete('/profissionais/{id}', \App\Application\Actions\Profissionais\ExcluirAction::class);

        // Especialidades
        $group->get('/especialidades', \App\Application\Actions\Especialidades\ListarAction::class);
        $group->post('/especialidades', \App\Application\Actions\Especialidades\SaveAction::class);

        // Agendas
        $group->get('/agendas', \App\Application\Actions\Agendas\ListarAction::class);
        $group->post('/agendas', \App\Application\Actions\Agendas\SaveAction::class);

        // Telemedicina
        $group->get('/telemedicina/sessoes', \App\Application\Actions\Telemedicina\ListarSessoesAction::class);
        $group->get('/telemedicina/sessoes/{id}', \App\Application\Actions\Telemedicina\VisualizarSessaoAction::class);
        $group->post('/telemedicina/sessoes', \App\Application\Actions\Telemedicina\CriarSessaoAction::class);

        // Unidades Hospitalares
        $group->get('/unidades-hospitalares', \App\Application\Actions\UnidadesHospitalares\ListarAction::class);
        $group->get('/unidades-hospitalares/{id}', \App\Application\Actions\UnidadesHospitalares\GetAction::class);
        $group->post('/unidades-hospitalares', \App\Application\Actions\UnidadesHospitalares\SaveAction::class);
        $group->put('/unidades-hospitalares/{id}', \App\Application\Actions\UnidadesHospitalares\UpdateAction::class);
        $group->delete('/unidades-hospitalares/{id}', \App\Application\Actions\UnidadesHospitalares\ExcluirAction::class);

        // Leitos
        $group->get('/leitos', \App\Application\Actions\Leitos\ListarAction::class);
        $group->get('/leitos/{id}', \App\Application\Actions\Leitos\GetAction::class);
        $group->post('/leitos', \App\Application\Actions\Leitos\SaveAction::class);
        $group->put('/leitos/{id}', \App\Application\Actions\Leitos\UpdateAction::class);
        $group->delete('/leitos/{id}', \App\Application\Actions\Leitos\ExcluirAction::class);

        // Estoque
        $group->get('/itens-estoque', \App\Application\Actions\Estoque\ListarItensAction::class);
        $group->get('/itens-estoque/{id}', \App\Application\Actions\Estoque\VisualizarItemAction::class);
        $group->post('/itens-estoque', \App\Application\Actions\Estoque\CriarItemAction::class);
        $group->put('/itens-estoque/{id}', \App\Application\Actions\Estoque\AtualizarItemAction::class);
        $group->delete('/itens-estoque/{id}', \App\Application\Actions\Estoque\ExcluirItemAction::class);
        $group->get('/movimentos-estoque', \App\Application\Actions\Estoque\ListarMovimentosAction::class);
        $group->post('/movimentos-estoque', \App\Application\Actions\Estoque\RegistrarMovimentoAction::class);

        // Transações Financeiras
        $group->get('/transacoes-financeiras', \App\Application\Actions\TransacoesFinanceiras\ListarAction::class);
        $group->get('/transacoes-financeiras/{id}', \App\Application\Actions\TransacoesFinanceiras\GetAction::class);
        $group->post('/transacoes-financeiras', \App\Application\Actions\TransacoesFinanceiras\SaveAction::class);
    })->add(new JwtMiddleware($key));
};
