// xEnableDrag r8, Copyright 2002-2007 Michael Foster (Cross-Browser.com)
// Part of X, a Cross-Browser Javascript Library, Distributed under the terms of the GNU LGPL

function xEnableDragVentana(id,fS,fD,fE)
{
//id Titulo ventana
  var mx = 0, my = 0, el = xGetElementById(id+"_TITULO");


  var el2 = xGetElementById(id);
var el6 = xGetElementById(id+"_B_INFERIOR");//quitar

  if (el) {
    el.xDragEnabled = true;
    el2.xDragEnabled = true;
el6.xDragEnabled = true;//quitar

    xAddEventListener(el, 'mousedown', dragStart, false);
xAddEventListener(el6, 'mousedown', dragStart, false);//quitar
     //xAddEventListener(el2, 'mousedown', dragStart, false);
  }
  // Private Functions
  function dragStart(e)
  {
    if (el.xDragEnabled) {
      var ev = new xEvent(e);
      xPreventDefault(e);
      mx = ev.pageX;
      my = ev.pageY;
      xAddEventListener(document, 'mousemove', drag, false);
      xAddEventListener(document, 'mouseup', dragEnd, false);
      if (fS) {
        fS(el2, ev.pageX, ev.pageY, ev);
      }
    }
  }
  function drag(e)
  {
    var ev, dx, dy;
    xPreventDefault(e);
    ev = new xEvent(e);
    dx = ev.pageX - mx;
    dy = ev.pageY - my;
    mx = ev.pageX;
    my = ev.pageY;
    if (fD) {
      fD(el2, dx, dy, ev);
    }
    else {
      xMoveTo(el2, xLeft(el2) + dx, xTop(el2) + dy);//xMoveTo(el, xLeft(el) + dx, xTop(el) + dy);
    }
  }
  function dragEnd(e)
  {
    var ev = new xEvent(e);
    xPreventDefault(e);
    xRemoveEventListener(document, 'mouseup', dragEnd, false);
    xRemoveEventListener(document, 'mousemove', drag, false);
    if (fE) {
      fE(el2, ev.pageX, ev.pageY, ev);
    }
    if (xEnableDrag.drop) {
      xEnableDrag.drop(el2, ev);
    }
  }
}

xEnableDrag.drops = []; // static property
