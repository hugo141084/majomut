function Mayusculas(texto) { //Función que convierte  las letras a mayusculas.  
    texto.value = texto.value.toUpperCase(); 
}

function formatoMoneda(num,prefix)  {  //funcion que da formato moneda formatNumber(1000,'$')  Para obtener lo siguiente: $1,000.00
    num = Math.round(parseFloat(num)*Math.pow(10,2))/Math.pow(10,2)  
    prefix = prefix || '';  
    num += '';  
    var splitStr = num.split('.');  
    var splitLeft = splitStr[0];  
    var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '.00';  
    splitRight = splitRight + '00';  
    splitRight = splitRight.substr(0,3);  
    var regx = /(\d+)(\d{3})/;  
    while (regx.test(splitLeft)) {  
        splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');  
    }  
    return prefix + splitLeft + splitRight;  
}  

function restablecerValores(input,icono1,icono2,icono3,modif){ //boton de restablecer del panel presupuesto
                         
        
        document.getElementById(input).style.background='#EEEEEE';
        if(modif==='guardado')
            document.getElementById(input).style.color= '#666666';
        
            
        document.getElementById(input).readOnly = true;
        
        document.getElementById(icono1).src='img/pencil_32.png';
        document.getElementById(icono1).style.cursor='pointer';
        
        document.getElementById(icono2).src='img/check_null.png';
        document.getElementById(icono2).style.cursor='auto';
        
        document.getElementById(icono3).src='img/refresh_null.png';
        document.getElementById(icono3).style.cursor='auto';
        
        jQuery(function($){$("#"+input).maskMoney('destroy');});
        
        return true;

}

function editarValores(input,icono1,icono2,icono3,modif){//boton de edicion del panel presupuesto
                         
        document.getElementById(input).style.background='#FFFFFF';
        if(modif==='guardado')
            document.getElementById(input).style.color= '#000000';      
        
        document.getElementById(input).readOnly = false;
        
        document.getElementById(icono1).src='img/pencil_32_null.png';
        document.getElementById(icono1).style.cursor='auto';
        
        document.getElementById(icono2).src='img/check.png';
        document.getElementById(icono2).style.cursor='pointer';
        
        document.getElementById(icono3).src='img/refresh.png';
        document.getElementById(icono3).style.cursor='pointer';
        
        jQuery(function($){$("#"+input).maskMoney({allowZero:true});});
            
        
        return true;
}
