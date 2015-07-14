<html>
<body>
<h3>{$subject}</h3>
<ul>
    {foreach $values as $label=>$value}
        <li><strong>{$label}</strong>: {$value}</li>
    {/foreach}
</ul>

<!-- action results foreach -->
<h3>Action Results</h3>
<ul>
    {foreach $actionResults as $label=>$value}
        <li><strong>{$label}</strong>: {$value}</li>
    {/foreach}
</ul>
</body>
</html>