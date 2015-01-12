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


function _dmie(event){var x=0,y=0;if(b_IE||b_OP){x=event.clientX+(b_IEComp?docElement.scrollLeft:0);y=event.clientY+(b_IEComp?docElement.scrollTop:0);}else{x=event.pageX;y=event.pageY;};return[x,y];};function dm_popup(mInd,hidePause,event,x,y){if(b_IE)event.returnValue=false;var menu=dm_menu[mInd],smVar=menu.m[1],xy=(x&&y)?[x,y]:_dmie(event);if(smVar){var obj=_dmni(smVar);if(obj.style.visibility=='visible'){clearTimeout(smVar.hideTimer);_dmmh(menu.m[0].shownChildID);window.status=''};menu.m[0].shownChildID=smVar.id;_dmzh(smVar.id);var docSize=_dmcs(menu),smSize=_dmos(_dmoi(smVar.id+'tbl'));with(smVar.smStyle){var w=(smW?parseInt(smW):smSize[2])+smVar.shadowLen,h=(smH?parseInt(smH):smSize[3])+smVar.shadowLen;};xy[0]=_dmO1(docSize[0],docSize[2],xy[0],0,w,1,1).xy;xy[1]=_dmO1(docSize[1],docSize[3],xy[1],0,h,1,0).xy;with(obj.style){left=xy[0]+'px';top=xy[1]+'px';};_dmfa(obj);if(hidePause>0)smVar.hideTimer=setTimeout("_dmmh('"+menu.m[0].shownChildID+"');window.status='';",hidePause);};return false;};
