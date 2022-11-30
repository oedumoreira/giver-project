var table = $('#users').DataTable({
    dom: 'Bfrtip',
    paging: false,
    info: false,
    searching: false,
    columns: [
        { data: 'id' },
        { data: 'nome' },
        { data: 'email' },
        { data: 'genero' },
        { data: 'cidade' },
        { data: 'endereco' },
        { data: 'empresa' },
        { data: 'cargo' },
        { data: 'site' },
    ],
    language: {
        'url': '//cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json'
    },
    buttons: [
        {
            text: 'Exportar',
            extend: 'collection',
            className: 'custom-html-collection',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5',
            ]
        },
        {
            text: 'Importar',
            action: function (e, dt, node, config) {
                $('#modal-import').modal('show');
            }
        },
        {
            text: 'Insights',
            action: function (e, dt, node, config) {
                $('#modal-insights').modal('show');
            }
        }
    ]
});

$('#btn-import').on('click', function(e) {

    if (!$('#filecsv').val()) {
        console.error('arquivo faltante');
        return;
    }

    var formData = new FormData();
    var file = $('#filecsv')[0].files[0];
    formData.append('filecsv', file);

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data: formData,  
        contentType:false,
        cache:false,
        processData:false,
        enctype: 'multipart/form-data',
        url: 'importcsv',
        beforeSend: function() {
            loading(true);
        },
        success: function(data) {
            $('#modal-import').modal('hide');
            getClientes();
            loading(false);
        },
        complete: function() {
            loading(false);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(`erro: ${xhr.responseText}`);
            loading(false);
        }
    })

});

function getClientes(page = 1) {

    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: `clientes?page=${page}`,
        beforeSend: function() {
            loading(true);
        },
        success: function(data) {
            table.clear().draw();

            $(data.itens).each(function(i, v) {
                table.rows.add([{
                    "id":       v.id,
                    "nome":     v.nome +' '+ v.sobrenome,
                    "email":    v.email,
                    "genero":   v.genero,
                    "cidade":   v.cidade,
                    "endereco": v.endereco_ip,
                    "empresa":  v.empresa,
                    "cargo":    v.cargo,
                    "site":     v.site,
                }]).draw()
            });
            $('#pagination').empty().append(data.pagination);
            
            loading(false);
        },
        complete: function() {
            loading(false);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(`erro: ${xhr.responseText}`);
            loading(false);
        }
    });
}

function getClientesSemSobrenome() {

    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: `clientes/semsobrenome`,
        success: function(data) {
            $('#qntdSemSobrenome').text(data);
            loading(false);
        }
    });
}

function getClientesSemGenero() {

    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: `clientes/semgenero`,
        success: function(data) {
            $('#qntdSemGenero').text(data);
            loading(false);
        }
    });
}

function getClientesSemEmailValido() {

    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: `clientes/sememailvalido`,
        success: function(data) {
            $('#qntdSemEmail').text(data);
            loading(false);
        }
    });
}

function changePage(value) {
    getClientes(value);
}

getClientes(1);
getClientesSemSobrenome();
getClientesSemGenero();
getClientesSemEmailValido();