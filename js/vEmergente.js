function vEmergente(clienteId, tituloInicial, xInicial, yInicial, anchoInicial, altoInicial, permiteMover, permiteRedimensionar, permiteMaxRestaurar, permiteCerrar, fnMover, fnRedimensionar, fnMaxRestaurar, fnCerrar, fnEnfocar){
    var este = this;
    
    //Agregado para centrar la ventana cuando no se especifican las coordenadas X,Y
    if ((!xInicial) || (!yInicial)) 
    {
        var centrar = true;
        var posVemergente = centrarVentana(anchoInicial, altoInicial);
        xInicial = posVemergente[0];
        yInicial = posVemergente[1];
    }
    //-- Fin centrar ventana
    
    este.pintar = function(dw, dh){
        var m = 1, b = 1;
        var m2 = 2 * m, b2 = 2 * b;
        W += dw;
        H += dh;
        xResizeTo(con, W, H);
        xMoveTo(este.cliente, m, m + xHeight(este.tbar));
        xResizeTo(este.cliente, W - m2 - b2, H - xHeight(este.tbar) - xHeight(este.sbar) - m2 - b2);
    };
    este.enfocar = function(e){
        con.style.zIndex = vEmergente.z++;
        for (var i in vEmergente.instancias) {
            vEmergente.instancias[i].tbar.className = 'veBarraTitulo';
            vEmergente.instancias[i].sbar.className = 'veBarraEstatus';
			
        }
        este.tbar.className = 'veBarraTituloEnf';
        //este.tbar.id = 'capaTitulo';
        este.sbar.className = 'veBarraEstatusEnf';
        if (fnEnfocar) {
            llamarFuncion(fnEnfocar);
        }
    };
    este.esconder = function(e){
		
        var c = true;
        if (fnCerrar) {
            c = llamarFuncion(fnCerrar);
			con.style.display = 'none';
        }
        if (c) {
            con.style.display = 'none';
            mostrada = false;
            xStopPropagation(e);
        }
    };
    este.mostrar = function(){
        //Agregado para centrar la ventana cuando no se especifican las coordenadas X,Y
        if (centrar) 
        {
            var posVemergente = centrarVentana(anchoInicial, altoInicial);
            xTop(este.con, posVemergente[1]);
        }
        //-- Fin centrar ventana
        
        con.style.display = 'block';
        mostrada = true;
        este.enfocar();
    };
    este.estatus = function(s){
        if (s) {
            este.sbar.firstChild.data = s;
        }
        return este.sbar.firstChild.data;
    };
    este.titulo = function(s){
        if (s) {
            este.tbar.firstChild.data = s;
        }
        return este.tbar.firstChild.data;
    };
    este.estaMostrada = function(){
        return mostrada;
    };
    function llamarFuncion(arrFuncion, XoW, YoH){
        if (arrFuncion && (tipode(arrFuncion) === 'array')) {
            var funcion = arrFuncion[0];
            var argumentos = [];
            if (arrFuncion.length > 1) {
                argumentos = arrFuncion.slice(1, arrFuncion.length - 1);
            }
            if ((funcion) && (typeof funcion === 'function')) {
                if (XoW) {
                    argumentos.push(XoW);
                }
                if (YoH) {
                    argumentos.push(YoH);
                }
                return funcion.apply(este, argumentos);
            }
            else {
                return false;
            }
        }
        else 
            if ((arrFuncion) && (typeof arrFuncion === 'function')) {
                return arrFuncion();
            }
            else {
                return false;
            }
    }
    function dragStart(){
        este.enfocar();
    }
    function barDrag(e, mdx, mdy){
        var x = xLeft(con) + mdx;
        var y = xTop(con) + mdy;
        if (x < 0) {
            x = 0;
        }
        if (y < 0) {
            y = 0;
        }
        xMoveTo(con, x, y);
        if (fnMover) {
            llamarFuncion(fnMover, x, y);
        }
    }
    function resDrag(e, mdx, mdy){
        este.pintar(mdx, mdy);
        if (fnRedimensionar) {
            llamarFuncion(fnRedimensionar, xWidth(este.cliente), xHeight(este.cliente));
        }
    }
    function maxClick(){
        if (maximizada) {
            maximizada = false;
            W = w;
            H = h;
            xMoveTo(con, x, y);
        }
        else {
            w = xWidth(con);
            h = xHeight(con);
            x = xLeft(con);
            y = xTop(con);
            xMoveTo(con, xScrollLeft(), xScrollTop());
            maximizada = true;
            W = xClientWidth() - 2;
            H = xClientHeight() - 2;
        }
        este.pintar(0, 0);
        if (fnMaxRestaurar) {
            llamarFuncion(fnMaxRestaurar, W, H);
        }
    }
    este.tbar = null;
    este.sbar = null;
    este.cliente = xGetElementById(clienteId);
    if (!este.cliente) {
        este.cliente = document.createElement('div');
        este.cliente.id = clienteId;
    }
    este.cliente.className += ' veCliente';
    este.cliente.style.display = 'block';
    var x, y, w, h, maximizada = false;
    var mostrada = false;
    vEmergente.instancias[clienteId] = este;
    var con = document.createElement('div');
    con.className = 'veContenedorExterno';
    este.con = con;
    if (permiteRedimensionar) {
        var rbtn = document.createElement('div');
        rbtn.className = 'veIconoRedim';
        rbtn.title = 'Arrastre para redimensionar';
    }
    if (permiteMaxRestaurar) {
        var mbtn = document.createElement('div');
        mbtn.className = 'veIconoMR';
        mbtn.title = 'Click para Maximizar/Restaurar';
    }
    if (permiteCerrar) {
        var cbtn = document.createElement('div');
        cbtn.className = 'veIconoCie';
        cbtn.title = 'Click para cerrar';
    }
    este.tbar = document.createElement('div');
    este.tbar.className = 'veBarraTitulo';
    
    if (permiteMover) {
        este.tbar.title = 'Arrastre para Mover';
        if (permiteMaxRestaurar) {
            este.tbar.title += ', ';
        }
    }
    if (permiteMaxRestaurar) {
        este.tbar.title += 'Double-Click para Maximizar/Restaurar';
    }
    este.tbar.appendChild(document.createTextNode(tituloInicial));
    este.sbar = document.createElement('div');
    este.sbar.className = 'veBarraEstatusEnf';
    este.sbar.title = 'Click para Enfocar';
   // este.sbar.appendChild(document.createTextNode('SIGA AIT'));
    con.appendChild(este.tbar);
    if (permiteMaxRestaurar) {
        este.tbar.appendChild(mbtn);
    }
    if (permiteCerrar) {
        este.tbar.appendChild(cbtn);
    }
    con.appendChild(este.cliente);
    con.appendChild(este.sbar);
    if (permiteRedimensionar) {
        este.sbar.appendChild(rbtn);
    }
    document.body.appendChild(con);
    var W = (anchoInicial) ? anchoInicial : xWidth(este.cliente), H = (altoInicial) ? altoInicial : xHeight(este.cliente);
    W = (W < 100) ? 100 : W;
    H = (H < 90) ? 90 : H;
    xMoveTo(con, xInicial, yInicial);
    este.pintar(0, 0);
    if (permiteMover) {
        xEnableDrag(este.tbar, dragStart, barDrag);
    }
    if (permiteRedimensionar) {
        xEnableDrag(rbtn, dragStart, resDrag);
    }
    con.onclick = este.enfocar;
    if (permiteMaxRestaurar) {
        mbtn.onclick = este.tbar.ondblclick = maxClick;
    }
    if (permiteCerrar) {
        cbtn.onclick = este.esconder;
        cbtn.onmousedown = xStopPropagation;
    }
    con.style.visibility = 'visible';
    este.enfocar();
    mostrada = true;
    xAddEventListener(window, 'unload', function(){
        if (permiteMover) {
            xDisableDrag(este.tbar);
        }
        if (rbtn) {
            xDisableDrag(rbtn);
        }
        con.onmousedown = con.onclick = null;
        if (mbtn) {
            mbtn.onclick = este.tbar.ondblclick = null;
        }
        if (cbtn) {
            cbtn.onclick = cbtn.onmousedown = null;
        }
        vEmergente.instancias[clienteId] = null;
        este = null;
    }, false);
}

vEmergente.z = 10000;
vEmergente.instancias = {};
if (typeof ventanaCES === 'undefined') {
    function ventanaCES(eleId, iniX, iniY, barId, resBtnId, maxBtnId, eliBtnId, funcion, argumentos){
        vEmergente(eleId, 'titulo', iniX, iniY, 0, 0, true, true, true, true, null, null, null, funcion, null);
    }
}