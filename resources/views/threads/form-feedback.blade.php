<div class="{{ $errors->has($object) ? 'invalid' : 'valid' }}-feedback">
    {{
    $errors->has($object) 
        ? $errors->first($object)
        :(count($errors) ? 'Your ' . $object . ' looks good!' : '')
    }}
</div>