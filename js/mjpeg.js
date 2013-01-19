function update(index)
{

    if (index == 0)
    {
        stop = 0;
        inc = 1;
    }
    else if (index == -1)
    {
        return;
    }
    
    if (stop == 1)
    {
        return;
    }
    if (preLoad[index]!= null)
    { 
        document.images['foto'].src = preLoad[index].src; 
        if (inc2 != 0)
        {
            index = index + inc2;
            inc2 = 0;
            update(index)
        }
        else
        {
            index = index + inc;
            setTimeout(function () { update(index) }, intervall)
        };
    }
    else
    {
        stop = 1;
        changeElement ('StartStop', 'Start', 'Start', 'start(1)')
        disableButton('PauseContinue');
        disableButton('ForwardRewind');
        removeElement('back');
        removeElement('forward');
        return;
    }

} 
function pause(value)
{
    if (value == 1)
    {
        inc = 0;
        addElement ("button", "back", "back", "Back", "inc2=-1");
        addElement ('button', 'forward', 'forward', 'Forward', 'inc2=1')
        changeElement ('PauseContinue', 'Continue >', 'Continue', 'pause(0)')
        disableButton('ForwardRewind');
    }
    else
    {
        inc = 1;
        removeElement('back');
        removeElement('forward');
        changeElement ('PauseContinue', 'Pause ||', 'Pause', 'pause(1)')
        enableButton('ForwardRewind');
    }
    
}

function start(value)
{
    if (value == 1)
    {
        changeElement ('StartStop', 'Stop', 'Stop', 'start(0)')
        enableButton('PauseContinue');
        enableButton('ForwardRewind');
        // changeElement ('PauseContinue', 'Pause', 'Pause', 'pause(1)')
        stop = 0;
        update(0);

    }
    else
    {
        stop = 1;
        changeElement ('StartStop', 'Start', 'Start', 'start(1)')
        changeElement ('PauseContinue', 'Pause ||', 'Pause', 'pause(1)')
        disableButton('PauseContinue');
        disableButton('ForwardRewind');
        removeElement('back');
        removeElement('forward');        

        
    }
    
}
function rewind(value)
{
    if (value == 1)
    {
        inc = -1;
        changeElement ('ForwardRewind', 'Forward >>', 'Forward', 'rewind(0)')
    }
    else
    {
        inc = 1;
        changeElement ('ForwardRewind', 'Rewind <<', 'Rewind', 'rewind(1)')
    }
}
function removeElement(id) {
    var p2 = document.getElementById (id);
    p2.parentNode.removeChild(p2);
}
function changeElement(id, value, name, onclick) {
    var element = document.getElementById (id);
    element.setAttribute("value", value);
    element.setAttribute("name", name);
    element.setAttribute("onClick", onclick);

}
function disableButton(id)
{
    // var element = document.getElementById (id);
    // element.setAttribute("disabled", true);
     document.getElementById(id).disabled=true;
}
function enableButton(id)
{
    //var element = document.getElementById (id);
    // element.setAttribute("disabled", false);
    document.getElementById(id).disabled=false;
}

function addElement (type, id, value, name, onclick) {

    //Create an input type dynamically.
    var element = document.createElement("input");

    //Assign different attributes to the element.
    element.setAttribute("type", type);
    element.setAttribute("id", id);
    element.setAttribute("value", value);
    element.setAttribute("name", name);
    element.setAttribute("onClick", onclick);

    var foo = document.getElementById("fooBar");
    //Append the element in page (in span).
    foo.appendChild(element);

}
window.onload=function(){
    disableButton('PauseContinue');
    disableButton('ForwardRewind');
};

