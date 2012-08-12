require 'logger'

log = Logger.new(STDOUT)
log.progname  = 'Test for Logger'

require 'find'
require 'fileutils'
require 'yaml'
require 'erb'

require 'lib/MyConfig'
require "lib/OSFunctions"
require 'lib/GitPath'
require 'lib/SVGConvert'
require 'lib/SVGConvertInkscape'
require 'lib/SVGConvertRsvg'
require 'lib/Decal'
require 'lib/DecalWorker'
require 'lib/Index'
require 'lib/IndexFacade'

app_root = File.expand_path(File.dirname(__FILE__))
loaded_data = YAML.load(ERB.new(File.read("config.yml")).result)
cfg = MyConfig.new(loaded_data)
cfg['app_root'] = app_root

tbrenderer = SVGConvert.factory(cfg.renderer).setWidth(cfg.thumbnail_width)
pngrenderer = SVGConvert.factory(cfg.renderer).setDpi(cfg.output_dpi)

worker = DecalWorker.new().setThumbnailRenderer(tbrenderer).setPngRenderer(pngrenderer)

indices = IndexFacade.new(cfg)
indices.setTemplate(File.join(cfg.app_root ,'templates','decal.template'))
Find.find(cfg.masterpath) do |file|	
	path = GitPath.new(file)
	if path.isSvg()		
	    d = Decal.new(file, cfg.masterpath, cfg.ghpath + '/decals')
	    
	    FileUtils.mkdir_p File.dirname(d.computeTargetPath())
	    indices.addDecal(d)
	    #worker.setDecal(d)
	    #worker.renderPng()
	    #worker.renderThumbnail()
	end
end
