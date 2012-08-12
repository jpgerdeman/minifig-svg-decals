class DecalWorker

	def setDecal(d)
		@decal = d
        self
    end

	def copySVG()
		copy(@decal.getSourcePath(), @decal.computeTargetPath())
	end

	def renderThumbnail()	
		converter = @thumbnailRenderer
		converter.setInfile(@decal.getSourcePath()).setOutfile(@decal.computeThumbnailPath()).execute()
	end

	def renderPng()	
		converter = @pngRenderer;		
		converter.setInfile(@decal.getSourcePath()).setOutfile(@decal.computePngPath()).execute()
	end
	
	def setThumbnailRenderer(renderer)
		@thumbnailRenderer = renderer
		self
	end

	def setPngRenderer(renderer)
		@pngRenderer = renderer
		self
	end
	
end
