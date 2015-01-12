//***********************************************
//  Javascript Menu (c) 2006 - 2008, by Deluxe-Menu.com
//  Trial Version
//
//  version 3.2.5
//  E-mail:  cs@deluxe-menu.com
//***********************************************

//***********************************************
// Obfuscated by Javascript Obfuscator
// http://javascript-source.com
//***********************************************


function _dmwe(d){with(keyMode){if(d>0){if(itVar.ind+d<smVar.i.length)itVar=smVar.i[itVar.ind+d];else itVar=smVar.i[0];}else{if(itVar.ind+d>=0)itVar=smVar.i[itVar.ind+d];else itVar=smVar.i[smVar.i.length-1];};if(itVar.text=='-'||itVar.isDisabled||itVar.isDeleted||!itVar.isVisible)_dmwe(d);if(smVar==rsmVar)ritVar=itVar;};};function _dmlp(d){with(keyMode)if(d>0){if(itVar.childID){_dmzh(itVar.childID);if(menu.saveNavigation)_dmhq(1);smVar=_dmvi(itVar.childID);itVar=smVar.i[0];}else{_dmsm(menu.ind);itVar=ritVar;smVar=rsmVar;_dmwe(+1);};}else{if(itVar==ritVar)return;_dmmh(smVar.id);itVar=_dmvi(smVar.rootItemID);smVar=menu.m[itVar.smInd];};};function _dmhq(over){with(keyMode){if(!itVar.isPressed)_dmh(itVar,over);if(!_dmFDIV)return;var iObj=_dmoi(itVar.id+'tbl');if(!iObj)return;var di=_dmos(iObj);if(di[2]>2&&di[3]>2)with(_dmFDIV.style){left=di[0]+1+'px';top=di[1]+1+'px';width=di[2]-2+'px';height=di[3]-2+'px';display='';};};};function _dmdk(mInd,hideSm){if(hideSm)_dmsm(mInd);with(keyMode){_dmhq(0);if(_dmFDIV)_dmFDIV.style.display='none';_dmFDIV=null;isEnabled=0;};};function _dmfi(){with(keyMode){if(dm_focus)_dmFDIV=dm_gE('dmFDIV'+_dmKSInd);for(var i=0;i<dm_menu.ln();i++)_dmsm(i);isEnabled=1;menu=dm_menu[_dmKSInd];rsmVar=menu.m[0];ritVar=rsmVar.i[0];smVar=rsmVar;itVar=ritVar;_dmhq(1);};};function _dmcc(code){if(b_OP&&b_VER<8)switch(code){case 57346:return 113;break;case 57354:return 121;break;case 57375:return 37;break;case 57373:return 38;break;case 57376:return 39;break;case 57374:return 40;break;};return code;};var _dmKSInd=0,_dmFDIV=null;function dm_ext_keystrokes(e,win){if(b_IE)e=win?win.event:event;var k=_dmcc(e.keyCode);if(keyMode.isEnabled){if(k==27){_dmdk(keyMode.menu.ind,1);return false;};if(e.ctrlKey&&k==dm_actKey&&dm_menu.ln()>1){_dmdk(keyMode.menu.ind,1);var _old=_dmKSInd;do{_dmKSInd++;if(_dmKSInd==dm_menu.ln())_dmKSInd=0;}while(dm_menu[_dmKSInd].isPopup&&_dmKSInd!=_old);_dmfi();return false;};};with(keyMode)if(!isEnabled){if(e.ctrlKey&&k==dm_actKey)_dmfi();else return true;}else{_dmhq(0);var f=0;if(smVar.isHoriz)switch(k){case 39:_dmwe(+1);f=1;break;case 37:_dmwe(-1);f=1;break;case 38:f=1;break;case 40:_dmlp(+1);f=1;break;}else switch(k){case 39:_dmlp(+1);f=1;break;case 37:_dmlp(-1);f=1;break;case 38:_dmwe(-1);f=1;break;case 40:_dmwe(+1);f=1;break;};_dmvg(smVar);if(k==13&&!itVar.isDisabled){if(menu.toggleMode!=-2)dm_ext_setPressedItem(menu.ind,itVar.smInd,itVar.ind,true);_dI1Ila(menu,itVar);_dmdk(keyMode.menu.ind,1);return false;};_dmhq(1);return(f?false:true);};return false;};
