var COUNT_ITEMS = document.getElementById('countItems');

function inputSearch(e, obj)
{
    setTimeout(function()
    {
        if(obj.value != '')
        {
            var _form = obj.closest('form');
            var url = location.protocol+'//'+location.hostname+'/pre-result-search/?s='+encodeURIComponent(_form['s'].value)+'&json=1';
            
            var xhr = new XMLHttpRequest();
            xhr.open('GET', url);
            
            xhr.send();
            
            xhr.onload = function()
            {
                if(parseInt(xhr.status) == 200)
                {
                    var response = JSON.parse(xhr.responseText);
                    
                    COUNT_ITEMS.innerHTML = response['format_total'];
                    COUNT_ITEMS.setAttribute('data-count', response['total']);
                    response = null;
                }
            }
        }
        else
        {
            COUNT_ITEMS.innerHTML = 0;
            COUNT_ITEMS.setAttribute('data-count', 0);
        }
    }, 500);
}

function search(e, obj)
{
    e.preventDefault();
    
    var _form = obj.closest('form');
    if(_form['s'].value != '' && parseInt(COUNT_ITEMS.getAttribute('data-count')) > 0)
    {
        _form.submit();
    }
}

