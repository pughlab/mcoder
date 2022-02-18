function afficher_cacher(id)
{
    if(document.getElementById(id).style.visibility=="hidden")
    {
        document.getElementById(id).style.visibility="visible";
        document.getElementById('bouton_'+id).innerHTML='Hide alignment';
    }
    else
    {
        document.getElementById(id).style.visibility="hidden";
        document.getElementById('bouton_'+id).innerHTML='Show alignment';
    }
    return true;
}