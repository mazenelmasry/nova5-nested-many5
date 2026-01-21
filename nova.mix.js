const mix = require('laravel-mix')
const webpack = require('webpack')
const path = require('path')
const fs = require('fs')

class NovaExtension {
  name() {
    return 'nova-extension'
  }

  register(name) {
    this.name = name
  }

  webpackPlugins() {
    return new webpack.ProvidePlugin({
      _: 'lodash',
      Errors: 'form-backend-validation',
    })
  }

  webpackConfig(webpackConfig) {
    webpackConfig.externals = {
      vue: 'Vue',
    }

  webpackConfig.resolve.alias = {
    ...(webpackConfig.resolve.alias || {}),
    'laravel-nova': path.join(novaResourcesPath(), 'mixins/packages.js'),
    '@': novaResourcesPath(),
  }

    webpackConfig.output = {
      uniqueName: this.name,
    }
  }
}

mix.extend('nova', new NovaExtension())

function novaResourcesPath() {
  const candidates = [
    path.join(__dirname, '../../vendor/laravel/nova/resources/js'),
    path.join(__dirname, '../laravel/nova/resources/js'),
  ]

  return candidates.find(candidate => fs.existsSync(candidate)) || candidates[0]
}
