class Index

    def initialize( path )
		@title = nil
		@path = nil
		@currentDecal = nil
		@template = nil
		@config = nil
		@logger = nil
		@replacebasepath = false
        @path = path
        setTitle(path.split('/')[-2])
    end
    
    def setTitle( title )    
        @title = title
    end
    
    def getTitle()    
        @title
    end
    
    def setPath( path )    
        @path = path        
        self
    end

    def getPath()    
        @path
    end

    def getTemplate()    
        @template
    end

    def setTemplate( template )    
        @template = template
        self
    end
        
    def getCurrentDecal()    
        @currentDecal
    end

    def setCurrentDecal( currentDecal )    
        @currentDecal = currentDecal
        self
    end
        
    def addDecal( decal )    
		puts @path
        @currentDecal = decal        
		html = renderHtml()
		appendIndex(html)
    end
    
    def replaceBasePath( oldpath, newpath )
		@replacebasepath = true
		@oldpath = oldpath
		@newpath = newpath
    end
    
    def relativePath(path, appendbaseurl = true)
		path.gsub!(@oldpath, '/')
		path.gsub!('//', '/')
		if appendbaseurl
			path = File.expand_path(path, @newpath)
		end
		
		path
	end
    
    def renderHtml()            
		$png = relativePath( @currentDecal.computePngPath(), true )
		$thumbnail = relativePath( @currentDecal.computeThumbnailPath(), true )
		$svg =  relativePath( @currentDecal.computeTargetPath(), true )
        $title = @currentDecal.getName()
        if( @currentDecal.isTLG() )
			$css = 'tlg'
		else
			$css = ''
		end
		load(getTemplate())
		#puts $html
		#puts '---------------'
		$html
    end
    
    def appendIndex(html)	        
		file = getPath()       
		open(file, 'a') do |f|
			f.puts html
		end
	end


    def reset()	
			file = getPath()
			puts "resetting #{file}"
			FileUtils::safe_unlink(file)			
			template = File.join(File.dirname(__FILE__), '..','templates','index.html')
			html = File.open(template, 'r')			
			html.each do |line|
				line.gsub!('%title%', getTitle())
				appendIndex(line)
			end
	end
end
