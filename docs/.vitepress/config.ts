import { defineConfig } from 'vitepress'

export default defineConfig({
  title: 'CakePHP DTO',
  description: 'Generate typed data transfer objects for CakePHP: first DTO, schemas, collections and application integration.',
  base: '/cakephp-dto/',
  cleanUrls: true,
  srcExclude: ['README.md', 'Examples.md', 'Generating.md'],
  head: [['link', { rel: 'icon', type: 'image/svg+xml', href: '/cakephp-dto/favicon.svg' }]],
  themeConfig: {
    logo: '/logo.svg',
    nav: [
      { text: 'Installation', link: '/guide/installation' },
      { text: 'Getting started', link: '/guide/getting-started' },
      { text: 'Reference', link: '/reference/configuration' },
    ],
    sidebar: [
      { text: 'Guide', items: [
        { text: 'Installation', link: '/guide/installation' },
        { text: 'Getting started', link: '/guide/getting-started' },
        { text: 'Definitions', link: '/guide/definitions' },
        { text: 'Using DTOs', link: '/guide/runtime' },
        { text: 'Collections', link: '/guide/collections' },
        { text: 'CakePHP integration', link: '/guide/integration' }
      ] },
      { text: 'Reference', items: [
        { text: 'Commands and CI', link: '/reference/commands' },
        { text: 'Configuration', link: '/reference/configuration' },
        { text: 'Importing schemas', link: '/reference/importing' },
        { text: 'Upgrading', link: '/reference/upgrading' }
      ] },
    ],
    search: { provider: 'local' },
    socialLinks: [{ icon: 'github', link: 'https://github.com/dereuromark/cakephp-dto' }],
    editLink: { pattern: 'https://github.com/dereuromark/cakephp-dto/edit/master/docs/:path' },
  },
})
