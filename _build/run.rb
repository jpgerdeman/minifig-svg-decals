#
# run `ruby.run.rb -h` for information on how to use this command
#
require 'logger'
require 'find'
require 'fileutils'
require 'yaml'
require 'erb'
require 'pp'
require 'optparse'
require 'ostruct'

app_root = File.expand_path(File.dirname(__FILE__))

require app_root + '/lib/LoggerTrait'
require app_root + '/lib/MyConfig'
require app_root + "/lib/OSFunctions"
require app_root + '/lib/GitPath'
require app_root + '/lib/SVGConvert'
require app_root + '/lib/SVGConvertInkscape'
require app_root + '/lib/SVGConvertRsvg'
require app_root + '/lib/Decal'
require app_root + '/lib/DecalWorker'
require app_root + '/lib/LazyDecalWorker'
require app_root + '/lib/Index'
require app_root + '/lib/IndexFacade'

class Command
	def initialize(arguments)
		parseoptions(arguments)
		# location of config file set in options
		readConfig()
	end

	def readConfig()
		app_root = File.expand_path(File.dirname(__FILE__))
		loaded_data = YAML.load(ERB.new(File.read(@options.config_file)).result)
		loaded_data['app_root'] = app_root
		@config = MyConfig.new(loaded_data)
		@config.masterpath = File.realpath(@config.masterpath)
	end

	def parseoptions(arguments)
	    # options object with defaults
	    options = OpenStruct.new
	    options.skip_render = false
	    options.skip_index = false
	    options.config_file = 'config.yml'
	    options.verbose = false

	    # define Option parser behaviour and fill options
	    optparse =OptionParser.new do |opt|
	      opt.banner = "
You should have two directories specified in your config.
The first is a checkout of the masterbranch the second a 
checkout of the gh-pages branch. It is best two use two 
clean checkouts, specifically for building the site, which
you do not use for your usual development.
	      
Usage: ruby run.rb\n\n"
	      opt.on("-r", "--skip-render", "do not render thumbnails or pngs") do |dir|
        	    options.skip_render = dir
	      end      
	      opt.on("-i", "--skip-index", "do not create indices") do |dir|
	            options.skip_index = dir
              end
	      opt.on("-c", "--config FILE", "supply custom config file") do |dir|
	            options.config_file = dir
              end
          opt.on("-v", "--verbose", "print verbose output") do |dir|
          	options.verbose = dir
          end
	      opt.on_tail("-h", "--help", "Show this message") do
        	    puts opt
	            exit
        	  end
	    end

	    # parse arguments
	    optparse.parse!(arguments)

	    @options = options
	  end

	def execute()		
		log = Logger.new(STDOUT)
		log.progname  = 'minifig build'
		if(@options.verbose)
			log.level = Logger::DEBUG
		else
			log.level = Logger::INFO
		end
		tbrenderer = SVGConvert.factory(@config.renderer).setWidth(@config.thumbnail_width)
		tbrenderer.setLogger( log )
		pngrenderer = SVGConvert.factory(@config.renderer).setDpi(@config.output_dpi)
		pngrenderer.setLogger( log )
		worker = DecalWorker.new().setThumbnailRenderer(tbrenderer).setPngRenderer(pngrenderer)		

		indices = IndexFacade.new(@config)
		indices.setLogger( log )
		indices.setTemplate(File.join(@config.app_root ,'templates','decal.template'))
		indices.replaceBasePath(@config.ghpath, @config.base_url)

		log.info('Looking for masterpath in ' + @config.masterpath)

		Find.find(@config.masterpath) do |file|	
			path = GitPath.new(file)
			if path.isSvg()		
			    d = Decal.new(file, @config.masterpath, @config.ghpath + '/decals')
	    
			    FileUtils.mkdir_p File.dirname(d.computeTargetPath())
			    unless( @options.skip_index )
				    indices.addDecal(d)
			    end

			    worker.setDecal(d)
			    unless( @options.skip_render )
				    worker.renderPng()
				    worker.renderThumbnail()
			    end
			end
		end
		unless( @options.skip_index )
			indices.writeMenu()
		end
	end
end


Command.new(ARGV).execute()
