# Facade to several indices
class IndexFacade < Index

	def initialize(config)
		@indices = Hash.new("nil")
		@logger = nil
		@config = config
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
		key = relativePath(file.dup, false)		
        unless( @indices.has_key?(key) )  
			i = Index.new(file)
			i.setTemplate(getTemplate())			
			i.reset()
			i.replaceBasePath(@config.ghpath, @config.base_url)			
            @indices[key] = i
        end
        return @indices[key]
    end
    
    def fetchGlobalIndex()
		puts 'fetchGlobalIndex'
		file = getGlobalIndexPath()		
		key = relativePath(file.dup, false)		
        unless( @indices.has_key?(key) )  
			i = Index.new(file)
			i.setTitle('Decal Overview')
			i.setTemplate(getTemplate())			
			i.reset()
			i.replaceBasePath(@config.ghpath, @config.base_url)			
            @indices[key] = i
        end        
         @indices[key]
    end
    
	def getLocalIndexPath()					
		File.join(File.dirname(@currentDecal.computeTargetPath()),'index.html')
	end
        
	def getGlobalIndexPath()			
		File.join(@config.ghpath,'decals/index.html')
	end
        
    def computeNestedDirectories( dirList )
		nestedDirList = Hash.new(nil)
		dirList.each_pair do |key,index|        
			path = key.dup()
            rPath  = relativePath(path, false)
            pathList = path.split('/')

            curLvl = nestedDirList 
            compoundDir = ''           
            pathList.each do |dir|
				if( dir != 'index.html' )
					compoundDir = File.join(compoundDir, dir)
					if( curLvl[compoundDir] == nil )
						curLvl[compoundDir] = Hash.new(nil)
					end
					curLvl = curLvl[compoundDir]
				end
            end
		end
		nestedDirList
	end

    def generateMenu( nestedList )    	        
        itemList = ''
        nestedList.each_pair do |dir,children|      
			title = dir.split('/')[-1]
			index = File.join(dir + '/index.html')
			pp index
			if( @indices.has_key?(index) )				
        		title = "<a href='{{site.url}}#{index}'>#{title}</a>"
			else
				title = "<span class='no-index'>#{title}</span>"
        	end
        	subList = generateMenu(children)        	
        	item = "<li>#{title}\n    #{subList}</li>\n"        		
        	itemList = itemList + item
        end
        
         '<ul>' + itemList + '</ul>'
    end
    	
    def writeMenu()        	            	
    	nestedList = computeNestedDirectories(@indices)
    	pp nestedList
    	pp @indices
        menu = generateMenu(nestedList)
        menufile = File.join(File.expand_path(File.expand_path(File.dirname(__FILE__)) + '/../../'),'_includes','decal_menu')
        FileUtils::safe_unlink(menufile)			        
        open(menufile, 'a') do |f|
			f.puts menu
		end
    end
end
