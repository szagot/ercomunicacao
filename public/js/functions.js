/**
 * Funções do sistema
 */

/**
 * Ativa ou remove a tela de espera
 * @param show boolean
 */
function waitScreen( show ) {
    if ( show ) {
        $( '#waitScreen' ).fadeIn( 500 );
    } else {
        $( '#waitScreen' ).fadeOut( 500 );
    }
}

/**
 * Abre a imagem na tela de wait (modal box)
 * @param src Caminho da imagem
 */
function modalImg( src ) {
    var $waitScreen = $( '#waitScreen' );
    var modalBox    = $waitScreen.find( '.modalImgBox' );

    // Ação de click
    $waitScreen.bind( 'click', function () {
        // Oculta a imagem e a remove
        modalBox.hide( 500, function () {
            modalBox.empty().removeClass( 'border' );
        } );
        // Oculta a wait screen e remove a ação de click
        waitScreen();
        $waitScreen.unbind( 'click' );
    } );

    // Adiciona a imagem ao Modalbox
    modalBox.append( '<img  class="img" src="' + src + '" alt="Imagem Grande"/>' );

    // Ativa a wait screen
    waitScreen( true );

    // Carrega a imagem via ajax para garantir que esta será carregada antes de ser mostrada
    $.get( src, function () {
        // Carrega a imagem
        modalBox.find( '.img' ).fadeIn( 500, function () {
            // Mostra o modal
            modalBox.show( 500 );
        } )
    } );
}

/**
 * Seleciona o texto do elemento
 *
 * @param elementId ID do elemento (apenas o texto, sem a hashtag #)
 */
function selectText( elementId ) {
    var doc  = document,
        text = doc.getElementById( elementId ),
        range,
        selection;
    if ( doc.body.createTextRange ) {
        range = document.body.createTextRange();
        range.moveToElementText( text );
        range.select();
    } else if ( window.getSelection ) {
        selection = window.getSelection();
        range     = document.createRange();
        range.selectNodeContents( text );
        selection.removeAllRanges();
        selection.addRange( range );
    }
}


/**
 * Transforma o HTML em texto simples
 */
function stripHTML( dirtyString ) {
    var container = document.createElement( 'div' );
    var text      = document.createTextNode( dirtyString );
    container.appendChild( text );
    return container.innerHTML;
}

/**
 * Cria uma mensagem de sistema
 * Dependencies: Bootstrap e jQuery
 *
 * @param msg Mensagem (pode ser HTML)
 * @param title Título da Mensagem
 * @param btnExtra Nome do botão extra
 * @param btnExtraCallback Callback do botão extra
 * @param type Tipo do alerta
 */
function Message( msg, title, btnExtra, btnExtraCallback, type ) {

    var modalId      = '#modalMsg';
    var modalIdTitle = modalId + 'Title';

    // Verifica se já foi criado um modal
    var modal = $( modalId );
    if ( modal.length ) {
        modal.remove();
    }

    // Cria modal em back
    modal = $(
        '<div id="' + modalId +
        '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="' + modalIdTitle + '">'
    );

    if ( !type || !type.match( /^(success|primary|danger|warning|info)$/ ) ) {
        type = 'primary';
    }

    var icon = '';
    switch ( type ) {
        case 'info':
            icon = 'glyphicon glyphicon-exclamation-sign';
            break;
        case 'success':
            icon = 'glyphicon glyphicon-ok-sign';
            break;
        case 'warning':
        case 'danger':
            icon = 'glyphicon glyphicon-exclamation-sign';
            break;
        default:
            icon = 'glyphicon glyphicon-comment';
    }

    var text = 'text-' + type;
    if ( type === 'primary' ) {
        text = '';
    }

    // Monta tela de base
    modal.append(
        '<div class="modal-dialog" role="document"><div class="modal-content panel-' + type + '">' +
        '<div class="modal-header panel-heading">' +
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span></button>' +
        '<h4 class="modal-title" id="' + modalIdTitle + '">' +
        '<i class="' + icon + '"></i> ' + (title || 'Mensagem') + '</h4>' +
        '</div><div class="modal-body panel-body ' + text + '">' + (msg || '') + '</div>' +
        '<div class="modal-footer panel-footer">' +
        '<button type="button" id="btnOk" class="btn btn-' + type + '" data-dismiss="modal">Ok</button>' +
        '</div></div></div>'
    );

    // Adiciona callbacks
    if ( btnExtra && btnExtraCallback ) {
        modal.find( '.modal-footer' ).append(
            '<button type="button" id="btnExtra" class="btn btn-' + type + '">' + btnExtra + '</button>'
        );
        modal.find( '#btnExtra' ).click( btnExtraCallback ).click( function () {
            modal.modal( 'hide' );
        } );
        modal.find( '#btnOk' ).text( 'Cancelar' ).removeClass( 'btn-' + type ).addClass( 'btn-default' );
    }

    $( 'body' ).append( modal );
    modal.modal( 'show' ).on( 'shown.bs.modal', function () {
        modal.find( '#btnOk' ).focus();
    } );
}

/**
 * Atalhos para Message
 */
function MessageInfo( msg, title, btnExtra, btnExtraCallback ) {
    Message( msg, title, btnExtra, btnExtraCallback, 'info' );
}
function MessageWarning( msg, title, btnExtra, btnExtraCallback ) {
    Message( msg, title, btnExtra, btnExtraCallback, 'warning' );
}
function MessageSuccess( msg, title, btnExtra, btnExtraCallback ) {
    Message( msg, title, btnExtra, btnExtraCallback, 'success' );
}
function MessageDanger( msg, title, btnExtra, btnExtraCallback ) {
    Message( msg, title, btnExtra, btnExtraCallback, 'danger' );
}
