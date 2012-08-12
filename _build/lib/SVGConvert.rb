class SVGConvert
	def initialize()
		@dpi = nil
		@width = nil
		@infile = nil
		@outfile = nil
		self
	end
	
	def SVGConvert.factory(converter)
		{ 'inkscape' => SVGConvertInkscape, 'rsvg' => SVGConvertRsvg }[converter].new()
	end

	def setInfile(path)
		@infile = path
		self
	end

	def setOutfile(path, format='png')
		@outfile = path
		@format = format
		self
	end

	def setDpi(dpi)
		@dpi = dpi
		self
	end

	def setWidth(width)
		@width = width
		self
	end

	def execute()
		cmd = generateCommand()
		puts "running command #{cmd}"
		output = system(cmd)
	end

	def generateCommand()
		cmd = 'echo PLEASE USE A CONCRETE CLASS! pseudoconvert'

		unless @width == nil
			cmd = cmd + " --width " + @width
		end

		unless @dpi == nil
			cmd = cmd + " --dpi " + @dpi.to_s()
		end

		cmd = cmd + @infile + ' > ' + @outfile

		puts cmd

		return cmd
	end
end


