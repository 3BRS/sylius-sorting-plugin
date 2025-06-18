const path = require('path');
//const fs = require('fs');

const Encore = require('@symfony/webpack-encore');

const SyliusAdmin = require('@sylius-ui/admin');
const SyliusShop = require('@sylius-ui/shop');

// Admin config
const adminConfig = SyliusAdmin.getWebpackConfig(path.resolve(__dirname));
//const adminConfig = SyliusAdmin.getBaseWebpackConfig(path.resolve(__dirname));

// Shop config
const shopConfig = SyliusShop.getWebpackConfig(path.resolve(__dirname));
//const shopConfig = SyliusShop.getBaseWebpackConfig(path.resolve(__dirname));

// Shared controllers
//const common_controllers = path.resolve(__dirname, './assets/controllers.json');
//const common_plugin_controllers = path.resolve(__dirname, '../../../assets/controllers.json');


// App shop config
Encore
    .setOutputPath('public/build/app/shop')
    .setPublicPath('/build/app/shop')
    .addEntry('app-shop-entry', './assets/shop/entrypoint.js')
    //.addEntry('app-shop-entry', path.resolve(__dirname, './assets/shop/entrypoint.js'))
    //.addAliases({
    //    '@vendor': path.resolve(__dirname, '../../vendor'),
    //})
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSassLoader()
    //.enableStimulusBridge(mergeControllers(
    //  'shop',
    //    [
    //        common_controllers,
    //        common_plugin_controllers,
    //        path.resolve(__dirname, './assets/shop/controllers.json'),
    //        path.resolve(__dirname, '../../../assets/shop/controllers.json'),
    //   ]
    //))
;

const appShopConfig = Encore.getWebpackConfig();

appShopConfig.externals = Object.assign({}, appShopConfig.externals, { window: 'window', document: 'document' });
appShopConfig.name = 'app.shop';

Encore.reset();

// App admin config
Encore
    .setOutputPath('public/build/app/admin')
    .setPublicPath('/build/app/admin')
    .addEntry('app-admin-entry', './assets/admin/entrypoint.js')
    //.addEntry('app-admin-entry', path.resolve(__dirname, './assets/admin/entrypoint.js'))
    //.addAliases({
    //   '@vendor': path.resolve(__dirname, '../../vendor'),
    //})
    .addEntry('threebrs-sorting-admin', path.resolve(__dirname, '../../src/Resources/assets/admin/sorting-entry.js'))
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSassLoader()
    //.enableStimulusBridge(mergeControllers(
    // 'admin',
    //    [
    //        common_controllers,
    //       common_plugin_controllers,
    //        path.resolve(__dirname, './assets/admin/controllers.json'),
    //        path.resolve(__dirname, '../../../assets/admin/controllers.json'),
    //   ]
    //))
;

const appAdminConfig = Encore.getWebpackConfig();

appAdminConfig.externals = Object.assign({}, appAdminConfig.externals, { window: 'window', document: 'document' });
appAdminConfig.name = 'app.admin';

module.exports = [shopConfig, adminConfig, appShopConfig, appAdminConfig];

/*

/**
 * Merge controllers.json from multiple files into one and store in cache
 * Used to merge both controllers.json fed by packages and the one from the app
 add an ending comment here 
function mergeControllers(name, filePaths, cacheDir = 'var/cache/webpack') {
   const merged = filePaths.reduce(
        (acc, filePath) => {
            if (!fs.existsSync(filePath)) {
                return acc;
            }
            const json = JSON.parse(fs.readFileSync(filePath, {encoding: 'utf-8'}));
            acc.controllers  = { ...acc.controllers,  ...json.controllers  };
            acc.entrypoints  = { ...acc.entrypoints,  ...json.entrypoints  };
            return acc;
        },
        { controllers: {}, entrypoints: {} }
    );

    const tmpDir = path.resolve(__dirname, cacheDir);
    if (!fs.existsSync(tmpDir)) fs.mkdirSync(tmpDir, { recursive: true });

    const outFile = path.join(tmpDir, `controllers.merged.${name}.json`);
    fs.writeFileSync(outFile, JSON.stringify(merged, null, 2), 'utf-8');

    return outFile;
}
*/