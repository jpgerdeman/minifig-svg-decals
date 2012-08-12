class IndexFacade < Index

	def initialize(config)
		@indices = Hash.new("nil")
		@logger = nil
		@config = config
		@indices['a'] = 100
	end
    
	def addDecal(decal)
		puts 'adding' + decal.to_s()
		@currentDecal = decal
		local = fetchLocalIndex()
		global = fetchGlobalIndex()
		puts "adding local"
        local.addDecal(decal)
        puts "adding global"
        global.addDecal(decal)        
	end
    
    def fetchLocalIndex()    
		file = getLocalIndexPath()
        unless( @indices.has_key?(file) )  
			i = Index.new(file)
			puts "TemplatePath"
			puts getTemplate()
			i.setTemplate(getTemplate())			
			i.reset()
			i.replaceBasePath(@config.ghpath, @config.base_url)			
            @indices[file] = i
        end
        return @indices[file]
    end
    
    def fetchGlobalIndex()
		puts 'fetchGlobalIndex'
		file = getGlobalIndexPath()
		puts file
        unless( @indices.has_key?(file) )  
			i = Index.new(file)
			i.setTitle('Decal Overview')
			i.setTemplate(getTemplate())			
			i.reset()
			i.replaceBasePath(@config.ghpath, @config.base_url)			
            @indices[file] = i
        end
        
         @indices[file]
    end
    
	def getLocalIndexPath()					
		File.join(File.dirname(@currentDecal.computeTargetPath()),'index.html')
	end
        
	def getGlobalIndexPath()			
		File.join(@config.ghpath,'decals/index.html')
	end
        
    def generateMenu()
        menu = ''
        @indices.each_pair do |path,index|        
            rPath  = relativePath(path)
            title = path.split('/')[-2]
            depth = path.split('/').count()            
            cls = "depth-" + depth
            menu = menu + "<li class='#{cls}'><a class='#{cls}' href='{{site.url}}#{rPath}'>#{title}</a></li>\n"
        end
        
         '<ul>' + menu + '</ul>'
    end
	
    def writeMenu()    
        menu = generateMenu()
        menufile = File.join(File.expand_path(File.expand_path(File.dirname(__FILE__)) + '/../../'),'_includes','decal_menu')
        FileUtils::safe_unlink(menufile)			        
        open(menufile, 'a') do |f|
			f.puts menu
		end
    end
end
