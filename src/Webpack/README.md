# Webpack provider for Onyx

Must be declared after Twig.

```
use Onyx\Providers;

// [...]

$this->register(new Providers\Twig());
$this->register(new Providers\Webpack());
```

Example in Twig views :

```
<html>
    <head>

    <!-- [...] -->

    {% block stylesheet %}
        {% for asset in ['vendor.scss', 'main.scss', 'css.css'] %}
            <link rel="stylesheet" type="text/css" href="{{ webpackAsset(asset) }}">
        {% endfor %}
    {% endblock %}

    {% if webpackChunkManifest is not empty %}
        <script>
        //<![CDATA[
        window.webpackManifest = {{ webpackChunkManifest|raw }}
        //]]>
        </script>
    {% endif %}
    </head>

    <body>

    <!-- [...] -->

    {% block javascript %}
        {% for asset in ['common.js', 'vendor.main.js', 'main.js'] %}
        <script src="{{ webpackAsset(asset) }}"></script>
        {% endfor %}
    {% endblock %}
    </body>

</html>

```
