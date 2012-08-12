class Decal
	def initialize (filepath, sourceroot, targetroot)
		@filepath = filepath
		@sourceroot = sourceroot
		@targetroot = targetroot

		parsename()
	end

	def parsename()
		filename = File.basename(@filepath, ".svg")		
		hastlg = filename.include? '.tlg'
		haspar = filename.include? '.('
		if hastlg || haspar
			filename.gsub!('.tlg','');
			@isTLG = true
		else
			@isTLG = false
		end

		@name = filename
	end

	def getSourcePath()
		@filepath
	end

	def getName()
		@name
	end

	def computeTargetPath()
		@filepath.gsub(@sourceroot,@targetroot)
	end

	def computePngPath()
		path = computeTargetPath()
		"#{path}.png"
	end

	def computeThumbnailPath()
		path = computeTargetPath()
		"#{path}.tb.png"
	end

	def isTLG()
		@isTLG
	end
end
