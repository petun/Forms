<html>
    <body>
        <ul>
        {foreach $values as $label=>$value}
            <li><strong>{$label}</strong>: {$value}</li>
        {/foreach}
        </ul>
    </body>
</html>