function Navegador()

{

    var IE=document.all != undefined;

    var OPERA = window.opera != undefined;

    if(OPERA) return "OPERA";

    if(IE) return "IE";

    if((window)&&(window.netscape)&&(window.netscape.security))

    {

        if(window.XML){return "FIREFOX15";}

        else return "FIREFOX10";

    }

    return "OTRO";

}

function Validar_Email(Cadena)

{

    var Punto = Cadena.substring(Cadena.lastIndexOf('.') + 1, Cadena.length);

    var Dominio = Cadena.substring(Cadena.lastIndexOf('@') + 1, Cadena.lastIndexOf('.'));

       var Usuario = Cadena.substring(0, Cadena.lastIndexOf('@'));

    var Reserv = "@/º\"\'+*{}\\<>?¿[]áéíóú#·¡!^*;,:";

       var valido = true;

    //Punto no debe tener caracteres especiales

    for (var Cont=0; Cont<Punto.length; Cont++)

    {

           X = Punto.substring(Cont,Cont+1);

           if (Reserv.indexOf(X)!=-1)

               valido = false;

       }

    //Dominio no debe tener caracteres especiales

    for (var Cont=0; Cont<Dominio.length; Cont++)

    {

           X = Dominio.substring(Cont,Cont+1);

           if (Reserv.indexOf(X)!=-1)

               valido = false;

       }

    //Usuario no debe tener caracteres especiales

    for (var Cont=0; Cont<Usuario.length; Cont++)

    {

           X = Usuario.substring(Cont,Cont+1);

           if (Reserv.indexOf(X)!=-1)

               valido = false;

       }

    //Verificacion de sintaxis básica

       if (Punto.length<2 || Dominio.length <1 || Cadena.lastIndexOf('.')<0 || Cadena.lastIndexOf('@')<0 || Usuario.length<1)

    {

           valido = false;

       }

       return valido;

}

function DownloadFile(arch_name,edicion)

{

//alert (edicion);

    if(edicion=="BI")

    {

/*if(confirm("El documento esta diponible para editarse\nDesea hacerlo?"))

window.open('download.php?archivo='+arch_name+"&save=true");

else

window.open('download.php?archivo='+arch_name+"&save=false");

}

*/

lolo= CargaArch(arch_name);



}

else if(edicion=="BO")

{

/*

if(confirm("El documento solo esta disponible para consulta"))

window.open('download.php?archivo='+arch_name+"&save=false");*/

        CargaArchger(arch_name)

}

else if(edicion=="F")

{

/*

if(confirm("El documento solo esta disponible para consulta"))

window.open('download.php?archivo='+arch_name+"&save=false");*/

        CargaArchgerF(arch_name)

}



}









function DownloadFile_ger(arch_name,edicion,file)

{

    if(edicion=="true")

    {

    files=file;

    //alert(files);

    lolo= CargaArch(arch_name,files);

    //    if(confirm("El documento esta diponible para editarse\nDesea hacerlo?"))

    //        window.open('download.php?archivo='+arch_name+"&save=true");

    //    else

    //        window.open('download.php?archivo='+arch_name+"&save=false");

    }

    else

    {

        //if(confirm("El documento solo esta disponible para consulta"))

        //    window.open('download.php?archivo='+arch_name+"&save=false");

        CargaArchger(arch_name)

    }

}
